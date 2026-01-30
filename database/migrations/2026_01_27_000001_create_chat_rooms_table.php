<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_rooms', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['order_chat', 'general_chat']);
            $table->foreignId('order_id')->nullable()->constrained()->onDelete('cascade');
            $table->foreignId('toko_id')->nullable()->constrained()->onDelete('cascade');
            $table->enum('status', ['active', 'closed'])->default('active');
            $table->timestamps();

            $table->index(['type', 'status']);
            $table->index('order_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_rooms');
    }
};