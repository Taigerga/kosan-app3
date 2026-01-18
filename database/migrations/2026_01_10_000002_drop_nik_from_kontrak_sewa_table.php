<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('kontrak_sewa', function (Blueprint $table) {
            if (Schema::hasColumn('kontrak_sewa', 'nik')) {
                $table->dropColumn('nik');
            }
        });
    }

    public function down()
    {
        Schema::table('kontrak_sewa', function (Blueprint $table) {
            $table->string('nik', 20)->after('id_kamar');
        });
    }
};
