<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * STEP 2: CREATE fresh product system with variants
     * - Table products baru (tanpa initial_stock & variants JSON)
     * - 4 table sistem varian baru
     * - Recreate relasi tables
     */
    public function up(): void
    {
        // 0. DROP IF EXISTS (safety check)
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('voucher_products');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('stock_distribution_logs');
        Schema::dropIfExists('stock_requests');
        Schema::dropIfExists('product_variant_stocks');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
        
        // 1. CREATE products table (FRESH - without initial_stock & variants)
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('sku')->unique();
            $table->string('ukuran');
            $table->string('jenis_bahan')->nullable();
            $table->enum('tipe', ['innerbox', 'masterbox']);
            $table->string('cetak')->nullable();
            $table->string('finishing')->nullable();
            $table->text('description')->nullable();
            $table->json('photos')->nullable();
            $table->string('video')->nullable();
            $table->decimal('price', 15, 2);
            $table->decimal('discount_price', 15, 2)->nullable();
            // ❌ REMOVED: initial_stock
            // ❌ REMOVED: variants (JSON)
            $table->decimal('rating', 3, 2)->default(0.00);
            $table->integer('review_count')->default(0);
            $table->json('tags')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes
            $table->index('sku');
            $table->index('is_active');
            $table->index('created_at');
        });
        
        // 2. CREATE product_variants table
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->enum('type', ['color', 'size']); // color = parent, size = child
            $table->string('name'); // "Merah", "Biru", "XL", "L"
            $table->string('photo')->nullable(); // hanya untuk color
            $table->decimal('price', 15, 2)->nullable(); // hanya untuk size
            $table->foreignId('parent_id')->nullable()->constrained('product_variants')->onDelete('cascade'); // size -> color
            $table->integer('stock_pusat')->default(0); // stok di warehouse/pusat
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'type']);
            $table->index('parent_id');
            $table->index('stock_pusat');
        });
        
        // 3. CREATE product_variant_stocks table (stok per varian per toko)
        Schema::create('product_variant_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->timestamps();
            
            // Unique: 1 varian hanya bisa ada 1x di 1 toko
            $table->unique(['product_id', 'variant_id', 'toko_id'], 'unique_variant_toko');
            
            // Indexes
            $table->index(['toko_id', 'product_id']);
            $table->index('variant_id');
            $table->index('stock');
        });
        
        // 4. CREATE stock_requests table (request stok dari toko)
        Schema::create('stock_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->enum('type', ['direct', 'request']); 
            // direct = superadmin distribute langsung
            // request = kepala toko/staff request (perlu approval)
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->json('items'); // [{variant_id: 1, quantity: 10}, {variant_id: 2, quantity: 5}]
            $table->text('notes')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['toko_id', 'status']);
            $table->index(['product_id', 'status']);
            $table->index(['status', 'created_at']);
        });
        
        // 5. CREATE stock_distribution_logs table (log setiap distribusi)
        Schema::create('stock_distribution_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->integer('quantity'); // jumlah yang didistribusikan
            $table->enum('type', ['in', 'out']); 
            // in = masuk ke toko
            // out = keluar dari warehouse
            $table->enum('source_type', ['direct', 'request']);
            $table->foreignId('source_id')->constrained('stock_requests')->onDelete('cascade');
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->integer('stock_before'); // stok warehouse sebelum
            $table->integer('stock_after'); // stok warehouse sesudah
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes untuk reporting
            $table->index(['product_id', 'created_at']);
            $table->index(['toko_id', 'created_at']);
            $table->index(['variant_id', 'created_at']);
            $table->index('performed_by');
        });
        
        // 6. RECREATE category_product (relasi many-to-many)
        Schema::create('category_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade');
            $table->timestamps();
            
            // Unique constraint
            $table->unique(['product_id', 'category_id']);
            
            // Indexes
            $table->index(['product_id', 'category_id']);
        });
        
        // 7. RECREATE voucher_products (relasi voucher ke produk)
        Schema::create('voucher_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained('vouchers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->timestamps();
            
            // Indexes
            $table->index(['voucher_id', 'product_id']);
        });
        
        // 8. RECREATE carts (keranjang belanja customer)
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variant_id')->nullable()->constrained('product_variants')->onDelete('cascade'); // 🆕 TAMBAH VARIANT
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 2);
            $table->timestamps();
            
            // Unique: 1 customer, 1 product, 1 variant, 1 toko
            $table->unique(['customer_id', 'product_id', 'variant_id', 'toko_id'], 'unique_cart_item');
            
            // Indexes
            $table->index(['customer_id', 'toko_id']);
            $table->index('variant_id');
        });
        
        // 9. RECREATE product_reviews
        Schema::create('product_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->integer('rating'); // 1-5
            $table->text('review')->nullable();
            $table->json('photos')->nullable();
            $table->boolean('is_approved')->default(false);
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'is_approved']);
            $table->index('user_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop semua table yang dibuat (reverse order)
        Schema::dropIfExists('product_reviews');
        Schema::dropIfExists('carts');
        Schema::dropIfExists('voucher_products');
        Schema::dropIfExists('category_product');
        Schema::dropIfExists('stock_distribution_logs');
        Schema::dropIfExists('stock_requests');
        Schema::dropIfExists('product_variant_stocks');
        Schema::dropIfExists('product_variants');
        Schema::dropIfExists('products');
    }
};