<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('chat_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('chat_room_id')->constrained()->onDelete('cascade');
            $table->morphs('participantable'); // participantable_id & participantable_type
            $table->enum('role', ['customer', 'kepala_toko', 'super_admin']);
            $table->timestamp('last_read_at')->nullable();
            $table->timestamp('joined_at')->useCurrent();
            $table->timestamps();

            $table->index(['chat_room_id', 'participantable_type', 'participantable_id'], 'chat_participant_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('chat_participants');
    }
};