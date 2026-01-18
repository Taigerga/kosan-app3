<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id('id_review');
            $table->foreignId('id_kos')->constrained('kos', 'id_kos')->onDelete('cascade');
            $table->foreignId('id_penghuni')->constrained('penghuni', 'id_penghuni')->onDelete('cascade');
            $table->foreignId('id_kontrak')->constrained('kontrak_sewa', 'id_kontrak')->onDelete('cascade');
            $table->decimal('rating', 2, 1); // CHECK constraint nanti di Model
            $table->text('komentar')->nullable();
            $table->string('foto_review', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};