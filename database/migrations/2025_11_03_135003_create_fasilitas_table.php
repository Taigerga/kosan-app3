<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('fasilitas', function (Blueprint $table) {
            $table->id('id_fasilitas');
            $table->string('nama_fasilitas', 100)->unique();
            $table->enum('kategori', ['umum', 'kamar_mandi', 'dapur', 'parkir', 'keamanan', 'lainnya'])->default('umum');
            $table->string('icon', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fasilitas');
    }
};