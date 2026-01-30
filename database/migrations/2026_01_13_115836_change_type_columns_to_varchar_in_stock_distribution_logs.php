<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * 🔥 BETTER SOLUTION: Ganti ENUM ke VARCHAR untuk fleksibilitas
     */
    public function up(): void
    {
        // Ubah type dari ENUM ke VARCHAR
        DB::statement("
            ALTER TABLE `stock_distribution_logs` 
            MODIFY COLUMN `type` VARCHAR(50) NOT NULL
        ");
        
        // Ubah source_type dari ENUM ke VARCHAR
        DB::statement("
            ALTER TABLE `stock_distribution_logs` 
            MODIFY COLUMN `source_type` VARCHAR(50) NOT NULL
        ");
        
        // Optional: Tambah index untuk performa query
        Schema::table('stock_distribution_logs', function (Blueprint $table) {
            $table->index('type');
            $table->index('source_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes dulu
        Schema::table('stock_distribution_logs', function (Blueprint $table) {
            $table->dropIndex(['type']);
            $table->dropIndex(['source_type']);
        });
        
        // Kembalikan ke ENUM (WARNING: bisa fail jika ada data dengan nilai di luar ENUM)
        DB::statement("
            ALTER TABLE `stock_distribution_logs` 
            MODIFY COLUMN `type` ENUM('in', 'out', 'manual_adjustment', 'transfer') NOT NULL
        ");
        
        DB::statement("
            ALTER TABLE `stock_distribution_logs` 
            MODIFY COLUMN `source_type` ENUM('direct', 'request', 'manual_edit', 'transfer') NOT NULL
        ");
    }
};