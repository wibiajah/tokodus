<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('voucher_quantity_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('voucher_id')->constrained()->onDelete('cascade');
            $table->integer('min_quantity'); // Minimal 10 pcs
            $table->decimal('discount_amount', 15, 2); // Potong 1000
            $table->timestamps();
            
            // Index untuk query cepat
            $table->index(['voucher_id', 'min_quantity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('voucher_quantity_discounts');
    }
};