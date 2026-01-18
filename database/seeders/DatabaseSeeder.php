<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        $this->call([
            PemilikSeeder::class,
            PenghuniSeeder::class,
            FasilitasSeeder::class,
            KosSeeder::class,
            KamarSeeder::class,
            KosFasilitasSeeder::class,
            KontrakSewaSeeder::class,
            PembayaranSeeder::class,
        ]);
    }
}