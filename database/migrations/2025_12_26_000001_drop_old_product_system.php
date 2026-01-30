<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * STEP 1: DROP old product system
     * - Hapus semua table lama yang terkait produk
     * - Fresh start untuk sistem varian baru
     */
    public function up(): void
    {
        // Drop foreign key constraints first (order penting!)
        Schema::disableForeignKeyConstraints();
        
        // 1. Drop table yang depend ke products
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('product_stocks');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('voucher_products');
        
        // 2. Drop products table
        Schema::dropIfExists('products');
        
        Schema::enableForeignKeyConstraints();
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Tidak bisa di-reverse karena data sudah hilang
        // Harus restore dari backup
        throw new Exception('Cannot reverse this migration. Restore from backup if needed.');
    }
};