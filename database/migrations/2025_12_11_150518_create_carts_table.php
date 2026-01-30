<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained('customers')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->foreignId('toko_id')->constrained('tokos')->onDelete('cascade');
            $table->integer('quantity')->default(1);
            $table->decimal('price', 15, 2); // Harga saat ditambahkan ke cart
            $table->timestamps();

            // Unique constraint: 1 customer hanya punya 1 item yang sama dari 1 toko
            $table->unique(['customer_id', 'product_id', 'toko_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('carts');
    }
};