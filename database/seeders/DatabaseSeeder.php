<?php
namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@contoh.com')],
            ['name' => 'Admin', 'role' => 'admin', 'password' => env('ADMIN_PASSWORD', 'ganti-password-ini')]
        );
    }
}
