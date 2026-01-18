<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pemilik', function (Blueprint $table) {
            $table->id('id_pemilik');
            $table->string('nama', 100);
            $table->string('no_hp', 20);
            $table->string('email', 100)->unique();
            $table->string('foto_profil', 255)->nullable();
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->text('alamat')->nullable();
            $table->enum('status_pemilik', ['aktif', 'nonaktif', 'pending'])->default('pending');
            $table->enum('role', ['pemilik'])->default('pemilik'); // HAPUS ADMIN DARI SINI
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pemilik');
    }
};