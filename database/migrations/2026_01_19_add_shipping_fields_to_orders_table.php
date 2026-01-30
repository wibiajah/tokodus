<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('resi_number')->nullable()->after('notes');
            $table->string('courier_name')->nullable()->after('resi_number');
            $table->string('courier_phone')->nullable()->after('courier_name');
            $table->string('courier_photo')->nullable()->after('courier_phone');
            $table->text('shipping_notes')->nullable()->after('courier_photo');
            $table->timestamp('shipped_at')->nullable()->after('shipping_notes');
            
            // Index untuk search resi
            $table->index('resi_number');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropIndex(['resi_number']);
            $table->dropColumn([
                'resi_number',
                'courier_name',
                'courier_phone',
                'courier_photo',
                'shipping_notes',
                'shipped_at'
            ]);
        });
    }
};