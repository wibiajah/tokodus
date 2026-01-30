<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('ukuran')->after('sku'); // Required
            $table->string('jenis_bahan')->nullable()->after('ukuran'); // Optional
            $table->enum('tipe', ['innerbox', 'masterbox'])->after('jenis_bahan'); // Required
            $table->string('cetak')->nullable()->after('tipe'); // Optional
            $table->string('finishing')->nullable()->after('cetak'); // Optional
        });
    }

    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['ukuran', 'jenis_bahan', 'tipe', 'cetak', 'finishing']);
        });
    }
};