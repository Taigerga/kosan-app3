<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('penghuni', function (Blueprint $table) {
            $table->id('id_penghuni');
            $table->string('nama', 100);
            $table->string('nik', 20)->unique()->nullable();
            $table->string('no_hp', 20);
            $table->string('email', 100)->unique();
            $table->enum('jenis_kelamin', ['L', 'P']);
            $table->date('tanggal_lahir')->nullable();
            $table->text('alamat')->nullable();
            $table->string('foto_profil', 255)->nullable();
            $table->string('foto_ktp', 255)->nullable();
            $table->string('username', 50)->unique();
            $table->string('password', 255);
            $table->enum('status_penghuni', ['calon', 'aktif', 'nonaktif', 'ditolak'])->default('calon');
            $table->enum('role', ['penghuni'])->default('penghuni');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('penghuni');
    }
};