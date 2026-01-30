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
         Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_type')->nullable()->after('shipping_cost'); // reguler/instant
            $table->decimal('shipping_distance', 8, 2)->nullable()->after('shipping_type'); // Jarak dalam KM
            $table->decimal('customer_latitude', 10, 8)->nullable()->after('shipping_distance');
            $table->decimal('customer_longitude', 11, 8)->nullable()->after('customer_latitude');
            $table->decimal('toko_latitude', 10, 8)->nullable()->after('customer_longitude');
            $table->decimal('toko_longitude', 11, 8)->nullable()->after('toko_latitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
      Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn([
                'shipping_type',
                'shipping_distance',
                'customer_latitude',
                'customer_longitude',
                'toko_latitude',
                'toko_longitude'
            ]);
        });
    }
};
