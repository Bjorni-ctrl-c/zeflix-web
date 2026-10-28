<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic test example.
     */
    public function test_the_application_returns_a_successful_response(): void
    {
        $response = $this->get('/');

        $response->assertOk()
            ->assertSee('Cerita pilihan.')
            ->assertSee('Mulai dalam tiga langkah')
            ->assertSee('Buka akses sekali')
            ->assertSee('Katalog yang mudah dijelajahi')
            ->assertSee('Tidak ada tagihan bulanan');
    }
}
