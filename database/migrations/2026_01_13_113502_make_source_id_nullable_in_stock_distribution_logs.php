<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('stock_distribution_logs', function (Blueprint $table) {
            // Make source_id nullable untuk support manual edit yang tidak punya parent request
            $table->unsignedBigInteger('source_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('stock_distribution_logs', function (Blueprint $table) {
            // Rollback: make source_id NOT NULL lagi
            $table->unsignedBigInteger('source_id')->nullable(false)->change();
        });
    }
};