<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemilik', function (Blueprint $table) {
            $table->string('nik', 20)->unique()->nullable()->after('email');
        });
    }

    public function down()
    {
        Schema::table('pemilik', function (Blueprint $table) {
            if (Schema::hasColumn('pemilik', 'nik')) {
                $table->dropUnique(['nik']);
                $table->dropColumn('nik');
            }
        });
    }
};
