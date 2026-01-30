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
        Schema::create('shipping_settings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // "Reguler" / "Instant"
            $table->string('slug')->unique(); // "reguler" / "instant"
            $table->decimal('price_per_km', 10, 2); // Tarif per KM
            $table->decimal('min_charge', 10, 2); // Minimal ongkir
            $table->integer('max_distance'); // Maksimal jarak (km)
            $table->boolean('is_active')->default(true); // Aktif/tidak
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipping_settings');
    }
};
