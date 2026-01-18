<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FasilitasSeeder extends Seeder
{
    public function run()
    {
        DB::table('fasilitas')->insert([
            // Umum
            ['nama_fasilitas' => 'WiFi', 'kategori' => 'umum', 'icon' => 'wifi', 'created_at' => now()],
            ['nama_fasilitas' => 'Laundry', 'kategori' => 'umum', 'icon' => 'laundry', 'created_at' => now()],
            ['nama_fasilitas' => 'Dapur Bersama', 'kategori' => 'umum', 'icon' => 'kitchen', 'created_at' => now()],
            
            // Kamar Mandi
            ['nama_fasilitas' => 'Kamar Mandi Dalam', 'kategori' => 'kamar_mandi', 'icon' => 'bath', 'created_at' => now()],
            ['nama_fasilitas' => 'Air Panas', 'kategori' => 'kamar_mandi', 'icon' => 'hot-water', 'created_at' => now()],
            
            // Parkir
            ['nama_fasilitas' => 'Parkir Motor', 'kategori' => 'parkir', 'icon' => 'motorcycle', 'created_at' => now()],
            ['nama_fasilitas' => 'Parkir Mobil', 'kategori' => 'parkir', 'icon' => 'car', 'created_at' => now()],
            
            // Keamanan
            ['nama_fasilitas' => 'CCTV', 'kategori' => 'keamanan', 'icon' => 'cctv', 'created_at' => now()],
            ['nama_fasilitas' => 'Security 24 Jam', 'kategori' => 'keamanan', 'icon' => 'security', 'created_at' => now()],
        ]);
    }
}