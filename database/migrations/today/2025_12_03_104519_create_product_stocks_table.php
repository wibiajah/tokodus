<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_stocks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->foreignId('toko_id')->constrained()->onDelete('cascade');
            $table->integer('stock')->default(0); // Stok yang diambil dari stok awal
            $table->timestamps();

            // Satu produk hanya punya satu stok per toko
            $table->unique(['product_id', 'toko_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_stocks');
    }
};