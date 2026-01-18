<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PemilikSeeder extends Seeder
{
    public function run()
    {
        DB::table('pemilik')->insert([
            [
                'nama' => 'Budi Santoso',
                'no_hp' => '081234567890',
                'email' => 'budi@pemilik.com',
                'username' => 'budi',
                'password' => Hash::make('password123'),
                'alamat' => 'Jl. Pemilik No. 123',
                'status_pemilik' => 'aktif',
                'nik' => null,
                'jenis_kelamin' => null,
                'tanggal_lahir' => null,
                'role' => 'pemilik',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}