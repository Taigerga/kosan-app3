<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Remove unique constraints from penghuni table
        Schema::table('penghuni', function (Blueprint $table) {
            $table->dropUnique('penghuni_email_unique');
            $table->dropUnique('penghuni_username_unique');
        });
    }

    public function down()
    {
        // Re-add unique constraints if needed
        Schema::table('penghuni', function (Blueprint $table) {
            $table->unique('nik');
            $table->unique('email');
            $table->unique('username');
        });
    }
};