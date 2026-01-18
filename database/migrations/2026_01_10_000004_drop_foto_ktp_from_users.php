<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemilik', function (Blueprint $table) {
            if (Schema::hasColumn('pemilik', 'foto_ktp')) {
                $table->dropColumn('foto_ktp');
            }
        });

        Schema::table('penghuni', function (Blueprint $table) {
            if (Schema::hasColumn('penghuni', 'foto_ktp')) {
                $table->dropColumn('foto_ktp');
            }
        });
    }

    public function down()
    {
        Schema::table('pemilik', function (Blueprint $table) {
            if (!Schema::hasColumn('pemilik', 'foto_ktp')) {
                $table->string('foto_ktp', 255)->nullable()->after('foto_profil');
            }
        });

        Schema::table('penghuni', function (Blueprint $table) {
            if (!Schema::hasColumn('penghuni', 'foto_ktp')) {
                $table->string('foto_ktp', 255)->nullable()->after('foto_profil');
            }
        });
    }
};
