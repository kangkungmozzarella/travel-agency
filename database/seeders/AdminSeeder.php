<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $email = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (! $email || ! $password || strlen($password) < 8) {
            throw new \RuntimeException('Set ADMIN_EMAIL and ADMIN_PASSWORD (at least 8 characters) before seeding the administrator.');
        }

        if (! User::where('email', $email)->exists()) {
            User::forceCreate([
                'name' => env('ADMIN_NAME', 'Admin OTEWE'),
                'email' => $email,
                'password' => bcrypt($password),
                'is_admin' => true,
            ]);
        }
    }
}
