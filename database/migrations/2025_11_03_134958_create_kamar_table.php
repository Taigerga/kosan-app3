<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('kamar', function (Blueprint $table) {
            $table->id('id_kamar');
            $table->foreignId('id_kos')->constrained('kos', 'id_kos')->onDelete('cascade');
            $table->string('nomor_kamar', 10);
            $table->enum('tipe_kamar', ['Standar', 'Deluxe', 'VIP', 'Superior', 'Ekonomi']);
            $table->decimal('harga', 12, 2);
            $table->string('luas_kamar', 20)->nullable();
            $table->integer('kapasitas')->default(1);
            $table->text('fasilitas_kamar')->nullable();
            $table->string('foto_kamar', 255)->nullable();
            $table->enum('status_kamar', ['tersedia', 'terisi', 'maintenance'])->default('tersedia');
            $table->timestamps();

            $table->unique(['id_kos', 'nomor_kamar']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('kamar');
    }
};