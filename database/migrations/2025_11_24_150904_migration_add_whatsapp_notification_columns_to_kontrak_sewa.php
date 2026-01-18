<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kontrak_sewa', function (Blueprint $table) {
            // Kolom untuk tracking notifikasi WhatsApp
            $table->timestamp('notif_menunggu_dikirim')->nullable();
            $table->timestamp('notif_disetujui_dikirim')->nullable();
            $table->timestamp('notif_tolak_dikirim')->nullable();
            $table->timestamp('notif_5hari_dikirim')->nullable();
            $table->timestamp('notif_habis_dikirim')->nullable();
        });

        Schema::table('pemilik', function (Blueprint $table) {
            $table->timestamp('notif_pengajuan_baru_dikirim')->nullable();
        });
    }

    public function down()
    {
        Schema::table('kontrak_sewa', function (Blueprint $table) {
            $table->dropColumn([
                'notif_menunggu_dikirim',
                'notif_disetujui_dikirim', 
                'notif_tolak_dikirim',
                'notif_5hari_dikirim',
                'notif_habis_dikirim'
            ]);
        });

        Schema::table('pemilik', function (Blueprint $table) {
            $table->dropColumn('notif_pengajuan_baru_dikirim');
        });
    }
};