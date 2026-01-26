<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Remove unique constraints from pemilik table
        Schema::table('pemilik', function (Blueprint $table) {
            $table->dropUnique(['email']);
            $table->dropUnique(['username']);
        });
    }

    public function down()
    {
        // Re-add unique constraints if needed
        Schema::table('pemilik', function (Blueprint $table) {
            $table->unique('email');
            $table->unique('username');
        });
    }
};