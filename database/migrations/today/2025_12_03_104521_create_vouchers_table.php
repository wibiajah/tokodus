<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Voucher Natal
            $table->string('name'); // Nama voucher
            $table->text('description')->nullable();
            $table->enum('discount_type', ['fixed', 'percentage']); // Potongan tetap atau persen
            $table->decimal('discount_value', 15, 2); // Nilai diskon
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('usage_limit')->nullable(); // Batas penggunaan
            $table->integer('usage_count')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};