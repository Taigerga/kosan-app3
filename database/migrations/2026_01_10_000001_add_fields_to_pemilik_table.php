<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('pemilik', function (Blueprint $table) {
            $table->enum('jenis_kelamin', ['L', 'P'])->nullable()->after('email');
            $table->date('tanggal_lahir')->nullable()->after('jenis_kelamin');
            $table->string('foto_ktp', 255)->nullable()->after('foto_profil');
        });
    }

    public function down()
    {
        Schema::table('pemilik', function (Blueprint $table) {
            if (Schema::hasColumn('pemilik', 'foto_ktp')) {
                $table->dropColumn('foto_ktp');
            }
            if (Schema::hasColumn('pemilik', 'tanggal_lahir')) {
                $table->dropColumn('tanggal_lahir');
            }
            if (Schema::hasColumn('pemilik', 'jenis_kelamin')) {
                $table->dropColumn('jenis_kelamin');
            }
        });
    }
};
