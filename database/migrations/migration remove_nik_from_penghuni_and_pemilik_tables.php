<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Hapus kolom nik dari tabel penghuni
        Schema::table('penghuni', function (Blueprint $table) {
            if (Schema::hasColumn('penghuni', 'nik')) {
                $table->dropColumn('nik');
            }
        });

        // Hapus kolom nik dari tabel pemilik
        Schema::table('pemilik', function (Blueprint $table) {
            if (Schema::hasColumn('pemilik', 'nik')) {
                $table->dropColumn('nik');
            }
        });
    }

    public function down()
    {
        // Restore kolom nik jika perlu rollback
        Schema::table('penghuni', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->unique()->after('id_penghuni');
        });

        Schema::table('pemilik', function (Blueprint $table) {
            $table->string('nik', 20)->nullable()->unique()->after('id_pemilik');
        });
    }
};