<?php

namespace Database\Seeders;

use App\Models\Genre;
use App\Models\User;
use Illuminate\Database\Seeder;
use RuntimeException;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');
        if (! filter_var($email, FILTER_VALIDATE_EMAIL) || ! is_string($password) || strlen($password) < 12) {
            throw new RuntimeException('Set ADMIN_EMAIL and an ADMIN_PASSWORD of at least 12 characters before seeding.');
        }

        User::updateOrCreate(
            ['email' => $email],
            ['name' => 'Admin Zeflix', 'role' => 'admin', 'password' => $password]
        );

        foreach (['Drama', 'Komedi', 'Aksi', 'Dokumenter', 'Romansa'] as $name) {
            Genre::firstOrCreate(['name' => $name]);
        }
    }
}
