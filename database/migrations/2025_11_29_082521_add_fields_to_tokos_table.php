<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('tokos', function (Blueprint $table) {
            $table->string('email')->nullable()->after('telepon');
            $table->text('googlemap')->nullable()->after('email');
            $table->string('foto')->nullable()->after('googlemap');
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('tidak_aktif')->after('foto');
        });
    }

    public function down()
    {
        Schema::table('tokos', function (Blueprint $table) {
            $table->dropColumn(['email', 'googlemap', 'foto', 'status']);
        });
    }
};