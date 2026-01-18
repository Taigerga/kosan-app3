<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('pengaturan_kos', function (Blueprint $table) {
            $table->id('id_pengaturan');
            $table->foreignId('id_kos')->constrained('kos', 'id_kos')->onDelete('cascade');
            $table->integer('notifikasi_pembayaran_h_min')->default(5);
            $table->decimal('denda_keterlambatan', 5, 2)->default(0.00);
            $table->integer('toleransi_keterlambatan')->default(7);
            $table->timestamps();

            $table->unique(['id_kos']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('pengaturan_kos');
    }
};