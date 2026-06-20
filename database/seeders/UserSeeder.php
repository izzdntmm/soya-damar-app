<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Buat akun Admin
        User::create([
            'nama'     => 'Owner Soya Damar',
            'email'    => 'admin@soyadamar.com',
            'no_hp'    => '08123456789',
            'alamat'   => 'Pati, Jawa Tengah',
            'role'     => 'admin',
            'password' => Hash::make('password123'),
        ]);

        // Buat akun Sales contoh
        User::create([
            'nama'     => 'Budi Sales',
            'email'    => 'budi@soyadamar.com',
            'no_hp'    => '08987654321',
            'alamat'   => 'Pati, Jawa Tengah',
            'role'     => 'sales',
            'password' => Hash::make('password123'),
        ]);
    }
}