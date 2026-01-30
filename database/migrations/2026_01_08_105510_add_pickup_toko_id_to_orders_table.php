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
            // Check if delivery_method doesn't exist, add it
            if (!Schema::hasColumn('orders', 'delivery_method')) {
                $table->string('delivery_method')->default('delivery')->after('shipping_address');
            }
            
            // Add pickup_toko_id
            if (!Schema::hasColumn('orders', 'pickup_toko_id')) {
                $table->unsignedBigInteger('pickup_toko_id')->nullable()->after('delivery_method');
                $table->foreign('pickup_toko_id')->references('id')->on('tokos')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            if (Schema::hasColumn('orders', 'pickup_toko_id')) {
                $table->dropForeign(['pickup_toko_id']);
                $table->dropColumn('pickup_toko_id');
            }
        });
    }
};