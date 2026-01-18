<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kos', function (Blueprint $table) {
            $table->id('id_kos');
            $table->foreignId('id_pemilik')->constrained('pemilik', 'id_pemilik')->onDelete('cascade');
            $table->string('nama_kos', 255);
            $table->text('alamat');
            $table->string('kecamatan', 100)->nullable();
            $table->string('kota', 100)->nullable();
            $table->string('provinsi', 100)->nullable();
            $table->string('kode_pos', 10)->nullable();
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->text('deskripsi')->nullable();
            $table->text('peraturan')->nullable();
            $table->enum('jenis_kos', ['putra', 'putri', 'campuran']);
            $table->enum('tipe_sewa', ['harian', 'bulanan', 'tahunan'])->default('bulanan');
            $table->string('foto_utama', 255)->nullable();
            $table->enum('status_kos', ['aktif', 'nonaktif', 'pending'])->default('pending');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('kos');
    }
};