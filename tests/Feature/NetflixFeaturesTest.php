<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NetflixFeaturesTest extends TestCase
{
    use RefreshDatabase;

    protected function createSubscribedUser(): User
    {
        $user = User::factory()->create();
        $tx = $user->transactions()->create([
            'order_id' => 'ORDER-TEST-1',
            'amount' => 1,
            'status' => 'paid',
        ]);
        $user->subscriptions()->create([
            'transaction_id' => $tx->id,
            'starts_at' => now(),
            'ends_at' => null,
        ]);

        return $user;
    }

    public function test_user_can_toggle_film_in_watchlist(): void
    {
        $user = $this->createSubscribedUser();
        $film = Film::create([
            'title' => 'Film Uji Coba',
            'slug' => 'film-uji-coba',
            'synopsis' => 'Sinopsis uji coba untuk demo mahasiswa',
            'status' => 'published',
            'access' => 'free',
            'match_rate' => 98,
        ]);

        // 1. Add to watchlist
        $response = $this->actingAs($user)->postJson(route('watchlist.toggle', $film));
        $response->assertOk()
            ->assertJson([
                'status' => 'ok',
                'in_watchlist' => true,
                'count' => 1,
            ]);
        $this->assertTrue($user->fresh()->isInWatchlist($film));

        // 2. Remove from watchlist
        $response2 = $this->actingAs($user)->postJson(route('watchlist.toggle', $film));
        $response2->assertOk()
            ->assertJson([
                'status' => 'ok',
                'in_watchlist' => false,
                'count' => 0,
            ]);
        $this->assertFalse($user->fresh()->isInWatchlist($film));
    }

    public function test_watchlist_page_displays_saved_films(): void
    {
        $user = $this->createSubscribedUser();
        $film = Film::create([
            'title' => 'Film Favorit Saya',
            'slug' => 'film-favorit-saya',
            'synopsis' => 'Sinopsis film favorit di daftar saya',
            'status' => 'published',
            'access' => 'free',
        ]);

        $user->watchlistFilms()->attach($film->id);

        $response = $this->actingAs($user)->get(route('watchlist'));
        $response->assertOk()
            ->assertSee('Daftar Saya')
            ->assertSee('Film Favorit Saya');
    }

    public function test_watch_progress_can_be_saved(): void
    {
        $user = $this->createSubscribedUser();
        $film = Film::create([
            'title' => 'Film Lanjutkan Menonton',
            'slug' => 'film-lanjutkan-menonton',
            'synopsis' => 'Sinopsis film untuk pelacakan progress',
            'status' => 'published',
            'access' => 'free',
        ]);

        $response = $this->actingAs($user)->postJson(route('watch.progress', $film), [
            'progress_percent' => 45,
            'seconds' => 300,
            'duration' => 600,
        ]);

        $response->assertOk()
            ->assertJson(['status' => 'ok', 'progress' => 45]);

        $this->assertDatabaseHas('watch_progresses', [
            'user_id' => $user->id,
            'film_id' => $film->id,
            'progress_percent' => 45,
        ]);
    }

    public function test_quick_view_modal_endpoint_returns_json(): void
    {
        $user = $this->createSubscribedUser();
        $film = Film::create([
            'title' => 'Film Pratinjau Cepat',
            'slug' => 'film-pratinjau-cepat',
            'synopsis' => 'Sinopsis lengkap pratinjau cepat ala netflix',
            'genre' => 'Aksi',
            'status' => 'published',
            'access' => 'free',
            'match_rate' => 97,
        ]);

        $response = $this->actingAs($user)->getJson(route('film.quick', $film));
        $response->assertOk()
            ->assertJson([
                'title' => 'Film Pratinjau Cepat',
                'genre' => 'Aksi',
                'match_rate' => 97,
                'can_watch' => true,
            ]);
    }

    public function test_catalog_search_filters_results(): void
    {
        $user = $this->createSubscribedUser();
        Film::create([
            'title' => 'Cyberpunk 2088',
            'slug' => 'cyberpunk-2088',
            'synopsis' => 'Sinopsis cyberpunk masa depan',
            'status' => 'published',
            'access' => 'free',
        ]);
        Film::create([
            'title' => 'Drama Pedesaan Sunyi',
            'slug' => 'drama-pedesaan-sunyi',
            'synopsis' => 'Kisah kehidupan di pedesaan',
            'status' => 'published',
            'access' => 'free',
        ]);

        $response = $this->actingAs($user)->get(route('catalog', ['q' => 'Cyberpunk']));
        $response->assertOk()
            ->assertSee('Cyberpunk 2088')
            ->assertDontSee('Drama Pedesaan Sunyi');
    }
}
