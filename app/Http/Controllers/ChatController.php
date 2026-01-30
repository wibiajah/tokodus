<?php

namespace App\Http\Controllers;

use App\Models\ChatRoom;
use App\Models\ChatParticipant;
use App\Models\ChatMessage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ChatController extends Controller
{
    /**
     * Get chat room atau create jika belum ada
     */
    protected function getOrCreateChatRoom($type, $orderId = null, $tokoId = null)
    {
        $query = ChatRoom::where('type', $type);

        if ($orderId) {
            $query->where('order_id', $orderId);
        }

        if ($tokoId) {
            $query->where('toko_id', $tokoId);
        }

        $chatRoom = $query->where('status', ChatRoom::STATUS_ACTIVE)->first();

        if (!$chatRoom) {
            $chatRoom = ChatRoom::create([
                'type' => $type,
                'order_id' => $orderId,
                'toko_id' => $tokoId,
                'status' => ChatRoom::STATUS_ACTIVE,
            ]);
        }

        return $chatRoom;
    }

    /**
     * Add participant to chat room
     */
    protected function addParticipant($chatRoom, $participantableType, $participantableId, $role)
    {
        // Check if already participant
        $exists = $chatRoom->participants()
            ->where('participantable_type', $participantableType)
            ->where('participantable_id', $participantableId)
            ->exists();

        if ($exists) {
            return;
        }

        ChatParticipant::create([
            'chat_room_id' => $chatRoom->id,
            'participantable_type' => $participantableType,
            'participantable_id' => $participantableId,
            'role' => $role,
            'joined_at' => now(),
        ]);
    }

    /**
     * Send message
     */
protected function storeChatMessage(Request $request, ChatRoom $chatRoom, $senderType, $senderId)
    {
        $data = [
            'chat_room_id' => $chatRoom->id,
            'sender_type' => $senderType,
            'sender_id' => $senderId,
            'message' => $request->message,
        ];

        // Handle file upload
        if ($request->hasFile('attachment')) {
            $file = $request->file('attachment');
            
            // Validate
            $request->validate([
                'attachment' => 'required|file|max:5120|mimes:jpg,jpeg,png,pdf', // 5MB max
            ]);

            // Determine type
            $mimeType = $file->getMimeType();
            $isImage = str_starts_with($mimeType, 'image/');

            // Store file
            $path = $file->store('chat-attachments', 'public');

            $data['attachment_path'] = $path;
            $data['attachment_type'] = $isImage ? ChatMessage::ATTACHMENT_IMAGE : ChatMessage::ATTACHMENT_FILE;
        }

        return ChatMessage::create($data);
    }

    /**
     * Get messages with pagination
     */
 protected function fetchMessages(ChatRoom $chatRoom, $perPage = 50)

    {
        return $chatRoom->messages()
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Mark messages as read
     */
    protected function markAsRead(ChatRoom $chatRoom, $participantableType, $participantableId)
    {
        $participant = $chatRoom->participants()
            ->where('participantable_type', $participantableType)
            ->where('participantable_id', $participantableId)
            ->first();

        if ($participant) {
            $participant->markAsRead();
        }
    }
}