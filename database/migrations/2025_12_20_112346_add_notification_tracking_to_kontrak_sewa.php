<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kontrak_sewa', function (Blueprint $table) {
            // Kolom untuk tracking pengingat
            $table->timestamp('notif_7hari_dikirim')->nullable();
            $table->timestamp('notif_3hari_dikirim')->nullable();
            $table->timestamp('notif_h1_dikirim')->nullable();
            $table->timestamp('notif_hari_ini_dikirim')->nullable();
            $table->timestamp('notif_terlambat_dikirim')->nullable();
            $table->timestamp('notif_perpanjangan_dikirim')->nullable();
        });
    }

    public function down()
    {
        Schema::table('kontrak_sewa', function (Blueprint $table) {
            $table->dropColumn([
                'notif_7hari_dikirim',
                'notif_3hari_dikirim',
                'notif_h1_dikirim',
                'notif_hari_ini_dikirim',
                'notif_terlambat_dikirim',
                'notif_perpanjangan_dikirim'
            ]);
        });
    }
};