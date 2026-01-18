<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Tambah kolom remember_token ke tabel penghuni
        Schema::table('penghuni', function (Blueprint $table) {
            $table->rememberToken()->after('password');
        });

        // Tambah kolom remember_token ke tabel pemilik
        Schema::table('pemilik', function (Blueprint $table) {
            $table->rememberToken()->after('password');
        });
    }

    public function down()
    {
        // Hapus kolom remember_token dari tabel penghuni
        Schema::table('penghuni', function (Blueprint $table) {
            $table->dropRememberToken();
        });

        // Hapus kolom remember_token dari tabel pemilik
        Schema::table('pemilik', function (Blueprint $table) {
            $table->dropRememberToken();
        });
    }
};