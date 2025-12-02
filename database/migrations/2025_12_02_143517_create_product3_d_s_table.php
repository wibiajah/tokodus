<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('products_3d', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            
            // Dimensi dalam cm
            $table->decimal('width', 8, 2);
            $table->decimal('height', 8, 2);
            $table->decimal('depth', 8, 2);
            
            // Material & Warna default
            $table->string('material')->default('cardboard');
            $table->string('default_color')->default('#e8d4a0');
            
            // Info bisnis
            $table->decimal('price', 10, 2)->nullable();
            $table->text('description_3d')->nullable();
            $table->integer('min_order')->default(1);
            $table->boolean('is_active')->default(true);
            
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('products_3d');
    }
};