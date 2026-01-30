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
         Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique()->nullable(); // Nullable untuk private voucher
            $table->string('name');
            $table->text('description')->nullable();
            
            // Jenis & Nilai Diskon
            $table->enum('discount_type', ['fixed', 'percentage']);
            $table->decimal('discount_value', 15, 2);
            $table->decimal('max_discount', 15, 2)->nullable(); // Max diskon untuk percentage
            $table->decimal('min_purchase', 15, 2)->default(0); // Minimal pembelian
            
            // Tanggal
            $table->date('start_date');
            $table->date('end_date');
            
            // Status & Limit
            $table->boolean('is_active')->default(true);
            $table->integer('usage_limit_total')->nullable(); // Total limit penggunaan voucher
            $table->integer('usage_limit_per_customer')->nullable(); // Limit per customer
            $table->integer('usage_count')->default(0); // Total sudah digunakan
            
            // Kombinasi voucher
            $table->boolean('can_combine')->default(false);
            
            // Distribusi
            $table->enum('distribution_type', ['public', 'private'])->default('public');
            // public = ada kode, bisa dipakai semua atau customer tertentu
            // private = tidak ada kode, langsung ke customer tertentu
            
            // Scope
            $table->enum('scope', ['all_products', 'specific_products', 'specific_categories'])->default('all_products');
            
            $table->timestamps();
        });

        // Table pivot: voucher_products (untuk voucher yang dibatasi produk tertentu)
        Schema::create('voucher_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Table pivot: voucher_categories (untuk voucher yang dibatasi kategori tertentu)
        Schema::create('voucher_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Table pivot: voucher_customers (untuk voucher yang dibatasi customer tertentu)
        Schema::create('voucher_customers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // Table history penggunaan voucher
        Schema::create('voucher_usages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('set null');
            $table->decimal('discount_amount', 15, 2);
            $table->decimal('order_total', 15, 2);
            $table->timestamp('used_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('voucher_usages');
        Schema::dropIfExists('voucher_customers');
        Schema::dropIfExists('voucher_categories');
        Schema::dropIfExists('voucher_products');
        Schema::dropIfExists('vouchers');
    }
};
