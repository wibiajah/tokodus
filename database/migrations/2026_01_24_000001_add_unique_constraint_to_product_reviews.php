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
        Schema::table('product_reviews', function (Blueprint $table) {
            // Tambah unique constraint: 1 customer = 1 review per produk per order
            $table->unique(
                ['customer_id', 'product_id', 'order_id'], 
                'unique_customer_product_order_review'
            );
            
            // Update default is_approved jadi TRUE (auto approved)
            $table->boolean('is_approved')->default(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropUnique('unique_customer_product_order_review');
            $table->boolean('is_approved')->default(false)->change();
        });
    }
};