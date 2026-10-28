<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Genre;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class R2PosterUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_uploads_poster_to_private_r2_and_saves_its_object_key(): void
    {
        config()->set('filesystems.disks.r2-posters.bucket', 'zeflix-posters');
        Storage::fake('r2-posters');
        Genre::create(['name' => 'Drama']);
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.films.store'), [
            'title' => 'Poster Privat',
            'synopsis' => 'Film uji poster privat.',
            'poster_file' => UploadedFile::fake()->image('poster.jpg')->size(256),
            'poster_url' => '',
            'genre' => 'Drama',
            'duration_minutes' => 2,
            'access' => 'free',
            'status' => 'draft',
            'video_provider' => 'youtube',
            'video_ref' => '',
            'rights_note' => '',
        ]);

        $response->assertRedirect(route('admin.films.index'));
        $this->assertDatabaseHas('films', ['title' => 'Poster Privat']);
        $film = Film::where('title', 'Poster Privat')->firstOrFail();
        $this->assertStringStartsWith('posters/', $film->poster_url);
        Storage::disk('r2-posters')->assertExists($film->poster_url);
    }

    public function test_admin_uploads_poster_to_local_storage_when_r2_is_not_configured(): void
    {
        config()->set('filesystems.disks.r2-posters.bucket', null);
        Storage::fake('public');
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->post(route('admin.films.store'), [
            'title' => 'Poster Lokal',
            'synopsis' => 'Film uji poster lokal.',
            'poster_file' => UploadedFile::fake()->image('poster.jpg')->size(256),
            'poster_url' => '',
            'genre' => '',
            'duration_minutes' => 2,
            'access' => 'free',
            'status' => 'draft',
            'video_provider' => 'youtube',
            'video_ref' => '',
            'rights_note' => '',
        ]);

        $response->assertRedirect(route('admin.films.index'));
        $film = Film::where('title', 'Poster Lokal')->firstOrFail();
        $this->assertStringStartsWith('local:posters/', $film->poster_url);
        $posterPath = substr($film->poster_url, strlen('local:'));
        Storage::disk('public')->assertExists($posterPath);
        $this->assertSame(Storage::disk('public')->url($posterPath), $film->posterImageUrl());
    }

    public function test_admin_cannot_upload_an_svg_as_a_poster(): void
    {
        Storage::fake('r2-posters');
        $admin = User::factory()->create(['role' => 'admin']);

        $response = $this->actingAs($admin)->from(route('admin.films.create'))
            ->post(route('admin.films.store'), [
                'title' => 'Poster Tidak Valid',
                'synopsis' => 'Film uji validasi poster.',
                'poster_file' => UploadedFile::fake()->create('poster.svg', 1, 'image/svg+xml'),
                'poster_url' => '',
                'genre' => '',
                'duration_minutes' => 2,
                'access' => 'free',
                'status' => 'draft',
                'video_provider' => 'youtube',
                'video_ref' => '',
                'rights_note' => '',
            ]);

        $response->assertRedirect(route('admin.films.create'))
            ->assertSessionHasErrors('poster_file');
        $this->assertDatabaseMissing('films', ['title' => 'Poster Tidak Valid']);
    }

    public function test_homepage_signs_private_r2_posters_and_keeps_external_poster_urls(): void
    {
        config()->set('filesystems.disks.r2-posters', [
            'driver' => 's3',
            'key' => 'test-poster-access-key',
            'secret' => 'test-poster-secret-key',
            'region' => 'auto',
            'bucket' => 'zeflix-posters',
            'endpoint' => 'https://account-id.r2.cloudflarestorage.com',
            'use_path_style_endpoint' => true,
            'throw' => true,
            'report' => true,
        ]);

        Film::create([
            'title' => 'Film Poster Privat',
            'slug' => 'film-poster-privat',
            'synopsis' => 'Film poster privat.',
            'poster_url' => 'posters/cover.webp',
            'access' => 'free',
            'status' => 'published',
            'video_provider' => 'youtube',
            'published_at' => now(),
        ]);

        Film::create([
            'title' => 'Film Poster Eksternal',
            'slug' => 'film-poster-eksternal',
            'synopsis' => 'Film poster eksternal.',
            'poster_url' => 'https://images.example.test/poster.jpg',
            'access' => 'free',
            'status' => 'published',
            'video_provider' => 'youtube',
            'published_at' => now(),
        ]);

        $user = User::factory()->create();
        $transaction = Transaction::create(['user_id' => $user->id, 'order_id' => 'R2-POSTER-ACCESS', 'amount' => 1, 'status' => 'paid']);
        Subscription::create(['user_id' => $user->id, 'transaction_id' => $transaction->id, 'starts_at' => now()]);

        $response = $this->actingAs($user)->get(route('home'));

        $response->assertOk()
            ->assertSee('https://account-id.r2.cloudflarestorage.com/zeflix-posters/posters/cover.webp')
            ->assertSee('X-Amz-Algorithm=AWS4-HMAC-SHA256')
            ->assertSee('X-Amz-Signature')
            ->assertSee('https://images.example.test/poster.jpg');
    }
}
