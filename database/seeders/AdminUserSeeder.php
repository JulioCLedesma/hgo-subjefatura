<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'jadiurna.hgo@outlook.com'],
            [
                'name' => 'JULIO CESAR LEDESMA MARTINEZ',
                'email_verified_at' => now(),
                'password' => Hash::make('Admin1234*'), // <-- contraseña temporal
                'is_admin' => true,
            ]
        );
    }
}
