<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PenghuniSeeder extends Seeder
{
    public function run()
    {
        DB::table('penghuni')->insert([
            [
                'nama' => 'Sari Indah',
                'nik' => '1234567890123456',
                'no_hp' => '081298765432',
                'email' => 'sari@penghuni.com',
                'jenis_kelamin' => 'P',
                'tanggal_lahir' => '1995-05-15',
                'alamat' => 'Jl. Penghuni No. 456',
                'username' => 'sari',
                'password' => Hash::make('password123'),
                'status_penghuni' => 'calon',
                'role' => 'penghuni',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        ]);
    }
}