<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('tokos', function (Blueprint $table) {
        $table->text('googlemap_iframe')->nullable()->after('googlemap');
    });
}


    /**
     * Reverse the migrations.
     */
public function down()
{
    Schema::table('tokos', function (Blueprint $table) {
        $table->dropColumn('googlemap_iframe');
    });
}
};
