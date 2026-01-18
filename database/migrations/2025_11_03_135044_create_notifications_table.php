<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id('id_notifikasi');
            $table->integer('id_user');
            $table->enum('user_type', ['penghuni', 'pemilik']);
            $table->string('judul', 255);
            $table->text('pesan');
            $table->enum('tipe', ['info', 'warning', 'success', 'danger'])->default('info');
            $table->enum('dibaca', ['ya', 'tidak'])->default('tidak');
            $table->string('link', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
};