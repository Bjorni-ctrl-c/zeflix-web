<?php

namespace Tests\Feature;

use App\Models\Film;
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

        $response = $this->get(route('watch', $film));

        $response->assertOk()
            ->assertSee('https://account-id.r2.cloudflarestorage.com/zeflix-videos/films/film-r2.mp4')
            ->assertSee('X-Amz-Algorithm=AWS4-HMAC-SHA256')
            ->assertSee('X-Amz-Signature');
    }
}
