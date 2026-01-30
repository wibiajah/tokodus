<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            // Timestamp kapan admin terakhir lihat review ini
            $table->timestamp('viewed_at')->nullable()->after('is_approved');
            $table->index('viewed_at'); // ✅ Index untuk query performance
        });
    }

    public function down()
    {
        Schema::table('product_reviews', function (Blueprint $table) {
            $table->dropColumn('viewed_at');
        });
    }
};