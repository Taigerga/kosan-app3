<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whatsapp_messages', function (Blueprint $table) {
            $table->id();
            $table->string('phone', 20)->index(); // Nomor tujuan
            $table->text('message'); // Isi pesan
            $table->enum('status', ['pending', 'processing', 'sent', 'failed'])->default('pending')->index();
            $table->integer('attempts')->default(0); // Jumlah percobaan kirim
            $table->string('whatsapp_message_id', 100)->nullable(); // ID dari WhatsApp (jika berhasil)
            $table->text('error')->nullable(); // Error message jika gagal
            $table->timestamp('processing_at')->nullable(); // Waktu mulai diproses
            $table->timestamp('sent_at')->nullable(); // Waktu berhasil dikirim
            $table->timestamp('failed_at')->nullable(); // Waktu gagal permanen
            $table->string('type', 50)->nullable()->index(); // Jenis notifikasi (contract_reminder, payment, etc)
            $table->json('metadata')->nullable(); // Data tambahan (kontrak_id, user_id, etc)
            $table->timestamps();
            
            // Index untuk optimasi query
            $table->index(['status', 'attempts', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_messages');
    }
};
