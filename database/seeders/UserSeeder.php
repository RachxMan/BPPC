<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::updateOrCreate(
            ['email' => 'admin@example.com'],
            [
                'nama_lengkap' => 'Admin BPPC',
                'username' => 'admin',
                'no_telp' => '081234567890',
                'email_verified_at' => now(),
                'password' => Hash::make('El.crackhead21'),
                'remember_token' => Str::random(10),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Admin baru dengan username 'nha'
        User::updateOrCreate(
            ['username' => 'nha'],
            [
                'nama_lengkap' => 'Admin Nha',
                'username' => 'nha',
                'email' => 'nha@example.com',
                'no_telp' => '081234567891',
                'email_verified_at' => now(),
                'password' => Hash::make('El.crackhead21'),
                'remember_token' => Str::random(10),
                'role' => 'admin',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // CA (Collection Agent)
        User::updateOrCreate(
            ['email' => 'ca@example.com'],
            [
                'nama_lengkap' => 'Collection Agent',
                'username' => 'ca_user',
                'no_telp' => '081298765432',
                'email_verified_at' => now(),
                'password' => Hash::make('password123'),
                'remember_token' => Str::random(10),
                'role' => 'ca',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Dummy users (opsional)
        User::factory()->count(2)->create();
    }
}
