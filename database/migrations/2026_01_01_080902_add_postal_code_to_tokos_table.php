<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // ========================================
        // ADD POSTAL_CODE TO TOKOS TABLE ONLY
        // ========================================
        if (Schema::hasTable('tokos')) {
            Schema::table('tokos', function (Blueprint $table) {
                // Check if column doesn't exist
                if (!Schema::hasColumn('tokos', 'postal_code')) {
                    $table->string('postal_code', 10)
                          ->nullable()
                          ->after('alamat')
                          ->index()
                          ->comment('Kode pos lokasi toko untuk filter jarak');
                    
                    echo "✅ Column 'postal_code' added to 'tokos' table\n";
                } else {
                    echo "⚠️ Column 'postal_code' already exists in 'tokos' table\n";
                }
            });
        } else {
            echo "❌ Table 'tokos' not found\n";
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // ========================================
        // ROLLBACK: REMOVE POSTAL_CODE FROM TOKOS
        // ========================================
        if (Schema::hasTable('tokos')) {
            Schema::table('tokos', function (Blueprint $table) {
                if (Schema::hasColumn('tokos', 'postal_code')) {
                    $table->dropIndex(['postal_code']); // Drop index first
                    $table->dropColumn('postal_code');
                    
                    echo "✅ Column 'postal_code' removed from 'tokos' table\n";
                }
            });
        }
    }
};