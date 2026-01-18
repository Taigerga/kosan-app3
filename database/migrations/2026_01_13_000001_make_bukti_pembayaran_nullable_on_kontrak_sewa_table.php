<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kontrak_sewa', function (Blueprint $table) {
            $table->string('bukti_pembayaran', 255)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('kontrak_sewa', function (Blueprint $table) {
            $table->string('bukti_pembayaran', 255)->nullable(false)->change();
        });
    }
};
