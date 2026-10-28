<?php

namespace Tests\Feature;

use App\Models\Film;
use App\Models\Subscription;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class R2VideoPlaybackTest extends TestCase
{
    use RefreshDatabase;

    public function test_r2_object_key_from_database_renders_as_a_signed_playback_url(): void
    {
        config()->set('filesystems.disks.r2', [
            'driver' => 's3',
            'key' => 'test-access-key',
            'secret' => 'test-secret-key',
            'region' => 'auto',
            'bucket' => 'zeflix-videos',
            'endpoint' => 'https://account-id.r2.cloudflarestorage.com',
            'use_path_style_endpoint' => true,
            'throw' => true,
            'report' => true,
        ]);

        $film = Film::create([
            'title' => 'Film R2',
            'slug' => 'film-r2',
            'synopsis' => 'Film untuk menguji pemutaran dari R2.',
            'access' => 'free',
            'status' => 'published',
            'video_provider' => 'r2',
            'video_ref' => 'films/film-r2.mp4',
            'rights_note' => 'Konten uji',
            'published_at' => now(),
        ]);
        $user = User::factory()->create();
        $transaction = Transaction::create(['user_id' => $user->id, 'order_id' => 'R2-VIDEO-ACCESS', 'amount' => 1, 'status' => 'paid']);
        Subscription::create(['user_id' => $user->id, 'transaction_id' => $transaction->id, 'starts_at' => now()]);

        $response = $this->actingAs($user)->get(route('watch', $film));

        $response->assertOk()
            ->assertSee('https://account-id.r2.cloudflarestorage.com/zeflix-videos/films/film-r2.mp4')
            ->assertSee('X-Amz-Algorithm=AWS4-HMAC-SHA256')
            ->assertSee('X-Amz-Signature');
    }

    public function test_legacy_youtube_url_is_converted_to_its_video_id_in_the_player_embed(): void
    {
        $film = Film::create([
            'title' => 'Film YouTube Lama',
            'slug' => 'film-youtube-lama',
            'synopsis' => 'Film untuk menguji URL YouTube lama.',
            'access' => 'free',
            'status' => 'published',
            'video_provider' => 'youtube',
            'video_ref' => 'https://youtu.be/AbC123_xYz0?feature=shared',
            'rights_note' => 'Konten uji.',
            'published_at' => now(),
        ]);
        $user = User::factory()->create();
        $transaction = Transaction::create(['user_id' => $user->id, 'order_id' => 'YOUTUBE-LEGACY-ACCESS', 'amount' => 1, 'status' => 'paid']);
        Subscription::create(['user_id' => $user->id, 'transaction_id' => $transaction->id, 'starts_at' => now()]);

        $this->actingAs($user)
            ->get(route('watch', $film))
            ->assertOk()
            ->assertSee('https://www.youtube-nocookie.com/embed/AbC123_xYz0?rel=0&modestbranding=1', false);
    }
}
