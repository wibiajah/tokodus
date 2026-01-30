<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // 1. Update existing 'processing' to 'paid'
        DB::statement("UPDATE orders SET status = 'paid' WHERE status = 'processing'");
        
        // 2. Alter enum
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','paid','shipped','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }

    public function down(): void
    {
        // Rollback: kembalikan ke enum lama
        DB::statement("UPDATE orders SET status = 'processing' WHERE status = 'paid'");
        DB::statement("ALTER TABLE orders MODIFY COLUMN status ENUM('pending','processing','shipped','completed','cancelled') NOT NULL DEFAULT 'pending'");
    }
};