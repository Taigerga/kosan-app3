<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add bank details to pemilik table
        Schema::table('pemilik', function (Blueprint $table) {
            $table->string('nama_bank', 50)->nullable();
            $table->string('nomor_rekening', 50)->nullable();
        });

        // Add date ranges to pembayaran table to support non-monthly payments
        Schema::table('pembayaran', function (Blueprint $table) {
            $table->date('tanggal_mulai_sewa')->nullable()->after('bulan_tahun');
            $table->date('tanggal_akhir_sewa')->nullable()->after('tanggal_mulai_sewa');
        });

        // Update tipe_sewa enum in kos table to include 'mingguan'
        // Using raw SQL because changing enum via Schema builder is tricky across drivers
        // Assuming MySQL/MariaDB which is standard for Laragon
        DB::statement("ALTER TABLE kos MODIFY COLUMN tipe_sewa ENUM('harian', 'mingguan', 'bulanan', 'tahunan') DEFAULT 'bulanan'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pemilik', function (Blueprint $table) {
            $table->dropColumn(['nama_bank', 'nomor_rekening']);
        });

        Schema::table('pembayaran', function (Blueprint $table) {
            $table->dropColumn(['tanggal_mulai_sewa', 'tanggal_akhir_sewa']);
        });

        // Revert enum (warning: if 'mingguan' data exists, this might be problematic, but standard down migration behavior)
        DB::statement("ALTER TABLE kos MODIFY COLUMN tipe_sewa ENUM('harian', 'bulanan', 'tahunan') DEFAULT 'bulanan'");
    }
};
