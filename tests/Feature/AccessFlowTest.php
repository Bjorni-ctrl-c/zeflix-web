<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use App\Services\Midtrans;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AccessFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_view_catalog_or_film_details(): void
    {
        $film = $this->publishedFilm();

        $this->get(route('catalog'))->assertRedirect(route('login'));
        $this->get(route('film', $film))->assertRedirect(route('login'));
        $this->get(route('home'))->assertDontSee($film->title);
    }

    public function test_registration_sends_user_to_one_time_payment_and_blocks_catalog(): void
    {
        $this->post(route('register'), [
            'name' => 'Nadia Zeflix',
            'email' => 'nadia@example.test',
            'password' => 'password-strong',
            'password_confirmation' => 'password-strong',
        ])->assertRedirect(route('checkout.page'));

        $this->get(route('home'))
            ->assertOk()
            ->assertSee('Mulai dalam tiga langkah')
            ->assertSee('Buka akses');
        $this->get(route('catalog'))->assertRedirect(route('checkout.page'));
        $this->get(route('checkout.page'))->assertOk()->assertSee('Rp1');
    }

    public function test_unpaid_user_can_see_the_public_landing_page_without_browsing_the_catalog(): void
    {
        $user = User::factory()->create();
        $film = $this->publishedFilm();

        $this->actingAs($user)->get(route('home'))
            ->assertOk()
            ->assertSee('Mulai dalam tiga langkah')
            ->assertSee('Semua yang kamu butuhkan untuk menemukan cerita.')
            ->assertSee('Lanjutkan ke pembayaran')
            ->assertDontSee($film->title);

        $this->get(route('catalog'))->assertRedirect(route('checkout.page'));
    }

    public function test_paid_user_gets_permanent_catalog_access(): void
    {
        $user = User::factory()->create();
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => 'ZEF-PAID-001',
            'amount' => 1,
            'status' => 'paid',
        ]);
        Subscription::create([
            'user_id' => $user->id,
            'transaction_id' => $transaction->id,
            'starts_at' => now(),
            'ends_at' => null,
        ]);
        $film = $this->publishedFilm();

        $this->actingAs($user)->get(route('catalog'))->assertOk()->assertSee($film->title);
        $this->get(route('home'))->assertOk()->assertSee('Jelajahi katalog')->assertDontSee('Buat akun sekarang');
        $this->get(route('home'))->assertSee('Mulai dalam tiga langkah')->assertSee('Semua yang kamu butuhkan untuk menemukan cerita.');
        $this->assertTrue($user->fresh()->hasActiveSubscription());
        $this->assertNull($user->fresh()->activeUntil());
    }

    public function test_midtrans_success_grants_permanent_access_idempotently(): void
    {
        config()->set('svod.midtrans.server_key', 'test-server-key');
        $user = User::factory()->create();
        $transaction = Transaction::create([
            'user_id' => $user->id,
            'order_id' => 'ZEF-PAID-002',
            'amount' => 1,
        ]);
        $notification = [
            'order_id' => $transaction->order_id,
            'status_code' => '200',
            'gross_amount' => '1.00',
            'transaction_status' => 'settlement',
            'fraud_status' => 'accept',
        ];
        $notification['signature_key'] = hash('sha512', $notification['order_id'].$notification['status_code'].$notification['gross_amount'].'test-server-key');
        $midtrans = app(Midtrans::class);

        $midtrans->apply($notification);
        $midtrans->apply($notification);

        $this->assertDatabaseHas('transactions', ['id' => $transaction->id, 'status' => 'paid']);
        $this->assertDatabaseCount('subscriptions', 1);
        $this->assertDatabaseHas('subscriptions', ['transaction_id' => $transaction->id, 'ends_at' => null]);
    }

    public function test_user_can_update_name_and_password(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)->put(route('account.update'), [
            'name' => 'Nama Baru',
            'password' => 'updated-password',
            'password_confirmation' => 'updated-password',
        ])->assertRedirect();

        $user->refresh();
        $this->assertSame('Nama Baru', $user->name);
        $this->assertTrue(Hash::check('updated-password', $user->password));
    }

    public function test_admin_can_manage_genres_and_rename_a_genre_used_by_a_film(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $this->post(route('admin.genres.store'), ['name' => 'Drama'])->assertRedirect();
        $genre = Genre::where('name', 'Drama')->firstOrFail();
        $film = Film::create([
            'title' => 'Film Drama',
            'slug' => 'film-drama',
            'synopsis' => 'Film untuk menguji genre.',
            'genre' => $genre->name,
        ]);

        $this->assertSame(1, $genre->films()->count());
        $this->get(route('admin.genres.index'))->assertOk()->assertSee('Drama')->assertSee('Jumlah film', false);
        $this->put(route('admin.genres.update', $genre), ['name' => 'Cerita'])->assertRedirect();
        $this->assertDatabaseHas('films', ['id' => $film->id, 'genre' => 'Cerita']);

        $this->delete(route('admin.genres.destroy', $genre))
            ->assertRedirect()
            ->assertSessionHas('err');
    }

    public function test_admin_routes_reject_regular_users(): void
    {
        $this->actingAs(User::factory()->create())
            ->get(route('admin.genres.index'))
            ->assertForbidden();
    }

    public function test_publishing_film_redirects_with_field_errors_when_video_or_rights_note_is_missing(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->from(route('admin.films.create'))
            ->post(route('admin.films.store'), [
                'title' => 'Film Belum Lengkap',
                'synopsis' => 'Film untuk menguji validasi penerbitan.',
                'genre' => '',
                'duration_minutes' => 2,
                'access' => 'free',
                'status' => 'published',
                'video_provider' => 'youtube',
                'video_ref' => '',
                'rights_note' => '',
            ])
            ->assertRedirect(route('admin.films.create'))
            ->assertSessionHasErrors([
                'video_ref' => 'Isi ID atau URL video sebelum menerbitkan film.',
                'rights_note' => 'Isi catatan hak penggunaan sebelum menerbitkan film.',
            ]);

        $this->assertDatabaseMissing('films', ['title' => 'Film Belum Lengkap']);
    }

    public function test_admin_can_publish_film_with_video_and_rights_note(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);

        $this->actingAs($admin)
            ->post(route('admin.films.store'), [
                'title' => 'Film Siap Tayang',
                'synopsis' => 'Film dengan informasi yang lengkap.',
                'poster_url' => '',
                'genre' => '',
                'duration_minutes' => 2,
                'access' => 'free',
                'status' => 'published',
                'video_provider' => 'youtube',
                'video_ref' => 'https://youtu.be/AbC123_xYz0?feature=shared',
                'rights_note' => 'Hak penggunaan sudah dikonfirmasi.',
            ])
            ->assertRedirect(route('admin.films.index'));

        $this->assertDatabaseHas('films', [
            'title' => 'Film Siap Tayang',
            'status' => 'published',
            'video_ref' => 'AbC123_xYz0',
            'rights_note' => 'Hak penggunaan sudah dikonfirmasi.',
        ]);
    }

    public function test_admin_login_returns_to_the_protected_dashboard(): void
    {
        $admin = User::factory()->create(['role' => 'admin', 'password' => 'admin-password']);

        $this->get(route('admin.dashboard'))->assertRedirect(route('login'));
        $this->post(route('login'), [
            'email' => $admin->email,
            'password' => 'admin-password',
        ])->assertRedirect(route('admin.dashboard'));
    }

    private function publishedFilm(): Film
    {
        return Film::create([
            'title' => 'Film Uji',
            'slug' => 'film-uji',
            'synopsis' => 'Film untuk pengujian akses.',
            'access' => 'premium',
            'status' => 'published',
            'video_provider' => 'youtube',
            'video_ref' => 'video-test',
            'rights_note' => 'Konten uji.',
            'published_at' => now(),
        ]);
    }
}
