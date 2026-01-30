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
        // 1. DROP old product_stocks table
        Schema::dropIfExists('product_stocks');
        
        // 2. CREATE product_variants table
        Schema::create('product_variants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->enum('type', ['color', 'size']); // color or size
            $table->string('name'); // nama varian (e.g., "Merah", "XL")
            $table->string('photo')->nullable(); // untuk color saja
            $table->decimal('price', 15, 2)->nullable(); // untuk size saja
            $table->foreignId('parent_id')->nullable()->constrained('product_variants')->onDelete('cascade'); // untuk size, parent = color
            $table->integer('stock_pusat')->default(0); // stok di warehouse
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'type']);
            $table->index('parent_id');
        });
        
        // 3. CREATE product_variant_stocks table
        Schema::create('product_variant_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->timestamps();
            
            // Unique constraint: 1 variant = 1 toko
            $table->unique(['product_id', 'variant_id', 'toko_id']);
            
            // Indexes
            $table->index(['toko_id', 'product_id']);
            $table->index('variant_id');
        });
        
        // 4. CREATE stock_requests table
        Schema::create('stock_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->enum('type', ['direct', 'request']); // direct = superadmin distribute, request = toko request
            $table->foreignId('requested_by')->constrained('users')->onDelete('cascade');
            $table->foreignId('approved_by')->nullable()->constrained('users')->onDelete('set null');
            $table->enum('status', ['pending', 'approved', 'rejected'])->default('pending');
            $table->json('items'); // [{variant_id: 1, quantity: 10}, ...]
            $table->text('notes')->nullable();
            $table->timestamp('requested_at')->nullable();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['toko_id', 'status']);
            $table->index(['product_id', 'status']);
            $table->index('created_at');
        });
        
        // 5. CREATE stock_distribution_logs table
        Schema::create('stock_distribution_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('variant_id')->constrained('product_variants')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->integer('quantity'); // qty yang didistribusikan
            $table->enum('type', ['in', 'out']); // in = masuk ke toko, out = keluar dari warehouse
            $table->enum('source_type', ['direct', 'request']); // direct atau request
            $table->foreignId('source_id')->constrained('stock_requests')->onDelete('cascade'); // ID dari stock_requests
            $table->foreignId('performed_by')->constrained('users')->onDelete('cascade');
            $table->integer('stock_before'); // stok warehouse sebelum distribusi
            $table->integer('stock_after'); // stok warehouse setelah distribusi
            $table->text('notes')->nullable();
            $table->timestamps();
            
            // Indexes
            $table->index(['product_id', 'created_at']);
            $table->index(['toko_id', 'created_at']);
            $table->index(['variant_id', 'created_at']);
        });
        
        // 6. ALTER products table - remove old columns
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['initial_stock', 'variants']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restore products table
        Schema::table('products', function (Blueprint $table) {
            $table->integer('initial_stock')->default(0)->after('discount_price');
            $table->json('variants')->nullable()->after('initial_stock');
        });
        
        // Drop new tables in reverse order
        Schema::dropIfExists('stock_distribution_logs');
        Schema::dropIfExists('stock_requests');
        Schema::dropIfExists('product_variant_stocks');
        Schema::dropIfExists('product_variants');
        
        // Recreate old product_stocks table
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->integer('stock')->default(0);
            $table->timestamps();
            
            $table->unique(['product_id', 'toko_id']);
        });
    }
};