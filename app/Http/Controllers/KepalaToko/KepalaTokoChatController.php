<?php

namespace App\Http\Controllers\KepalaToko;

use App\Http\Controllers\ChatController;
use App\Models\ChatRoom;
use App\Models\User;
use App\Models\ChatParticipant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class KepalaTokoChatController extends ChatController
{
    /**
     * Show inbox/list chat rooms
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        $toko = $user->toko;

        if (!$toko) {
            abort(403, 'Anda tidak memiliki toko.');
        }

        // Get all chat rooms untuk toko ini
        $query = ChatRoom::where('toko_id', $toko->id)
            ->with(['order', 'latestMessage.sender', 'participants.participantable'])
            ->active();

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter unread only
        if ($request->has('unread') && $request->unread == '1') {
            $query->whereHas('participants', function ($q) use ($user) {
                $q->where('participantable_type', User::class)
                    ->where('participantable_id', $user->id)
                    ->where(function ($subQ) {
                        $subQ->whereNull('last_read_at')
                            ->orWhereColumn('last_read_at', '<', 'chat_rooms.updated_at');
                    });
            });
        }

        $chatRooms = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Get unread counts by type
        $stats = [
            'total' => ChatRoom::where('toko_id', $toko->id)->active()->count(),
            'order_chat' => ChatRoom::where('toko_id', $toko->id)->orderChat()->active()->count(),
            'general_chat' => ChatRoom::where('toko_id', $toko->id)->generalChat()->active()->count(),
            'unread' => $this->getUnreadCount($toko->id, $user->id),
        ];

        return view('kepala-toko.chat.index', compact('chatRooms', 'stats'));
    }

    /**
     * 🔥 NEW: Get chat list untuk AJAX polling
     */
    public function getChatList(Request $request)
    {
        $user = auth()->user();
        $toko = $user->toko;

        if (!$toko) {
            return response()->json(['success' => false], 403);
        }

        // Get all chat rooms untuk toko ini
        $query = ChatRoom::where('toko_id', $toko->id)
            ->with(['order', 'latestMessage.sender', 'participants.participantable'])
            ->active();

        // Filter by type
        if ($request->has('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        // Filter unread only
        if ($request->has('unread') && $request->unread == '1') {
            $query->whereHas('participants', function ($q) use ($user) {
                $q->where('participantable_type', User::class)
                    ->where('participantable_id', $user->id)
                    ->where(function ($subQ) {
                        $subQ->whereNull('last_read_at')
                            ->orWhereColumn('last_read_at', '<', 'chat_rooms.updated_at');
                    });
            });
        }

        $chatRooms = $query->orderBy('updated_at', 'desc')->paginate(20);

        // Get stats
        $stats = [
            'total' => ChatRoom::where('toko_id', $toko->id)->active()->count(),
            'order_chat' => ChatRoom::where('toko_id', $toko->id)->orderChat()->active()->count(),
            'general_chat' => ChatRoom::where('toko_id', $toko->id)->generalChat()->active()->count(),
            'unread' => $this->getUnreadCount($toko->id, $user->id),
        ];

        // Format chat rooms
        $formattedRooms = $chatRooms->map(function ($room) use ($user) {
            $customer = $room->participants->where('role', 'customer')->first();
            $unread = $room->getUnreadCount(User::class, $user->id);

            return [
                'id' => $room->id,
                'type' => $room->type,
                'type_label' => $room->type === 'order_chat' ? '📦 Order #' . $room->order->order_number : '💬 General Chat',
                'order_number' => $room->type === 'order_chat' ? $room->order->order_number : null,
                'customer_name' => $customer->participantable->full_name ?? 'Unknown',
                'unread_count' => $unread,
                'latest_message' => $room->latestMessage ? \Str::limit($room->latestMessage->message ?? '📎 Attachment', 50) : null,
                'updated_at_human' => $room->latestMessage
                    ? $room->latestMessage->created_at->diffForHumans()
                    : $room->updated_at->diffForHumans(),
                'updated_at_iso' => $room->latestMessage
                    ? $room->latestMessage->created_at->toIso8601String()
                    : $room->updated_at->toIso8601String(), // 🔥 NEW: ISO timestamp untuk client-side calculation
                'url' => route('kepala-toko.chat.show', $room->id),
            ];
        });

        return response()->json([
            'success' => true,
            'rooms' => $formattedRooms,
            'stats' => $stats,
            'pagination' => [
                'current_page' => $chatRooms->currentPage(),
                'last_page' => $chatRooms->lastPage(),
                'total' => $chatRooms->total(),
            ],
        ]);
    }

    /**
     * Show chat room
     */
    public function show(ChatRoom $chatRoom)
    {
        $user = auth()->user();
        $toko = $user->toko;

        // Validasi: chat room milik toko ini
        if ($chatRoom->toko_id !== $toko->id) {
            abort(403, 'Unauthorized');
        }

        // Load relationships
        $chatRoom->load(['order.customer', 'toko', 'participants.participantable']);

        // Get messages
        $messages = $this->fetchMessages($chatRoom);

        // Mark as read
        $this->markAsRead($chatRoom, User::class, $user->id);

        $chatRoom->messages()
            ->where(function ($query) use ($user) {
                $query->where('sender_type', '!=', User::class)
                    ->orWhere('sender_id', '!=', $user->id);
            })
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return view('kepala-toko.chat.show', compact('chatRoom', 'messages'));
    }

    /**
     * Send message dengan rate limiting
     * ✅ FIX 1.2: Unified response format
     */
    public function sendMessage(Request $request, ChatRoom $chatRoom)
    {
        $user = auth()->user();
        $toko = $user->toko;

        // Validasi
        if ($chatRoom->toko_id !== $toko->id) {
            abort(403, 'Unauthorized');
        }

        // Rate limiting: 20 messages per minute
        $key = 'chat-send:' . $user->id;

        if (RateLimiter::tooManyAttempts($key, 20)) {
            $seconds = RateLimiter::availableIn($key);
            return response()->json([
                'success' => false,
                'message' => "Terlalu banyak pesan. Coba lagi dalam {$seconds} detik."
            ], 429);
        }

        // Validate
        $request->validate([
            'message' => 'required_without:attachment|string|max:1000',
            'attachment' => 'nullable|file|max:5120|mimes:jpg,jpeg,png,pdf',
        ]);

        // Send message
        $message = $this->storeChatMessage($request, $chatRoom, User::class, $user->id);

        // Hit rate limiter
        RateLimiter::hit($key, 60);

        // TODO: Broadcast event (Phase 3)

        // ✅ FIX 1.2: Format response sama seperti getNewMessages
        $isMe = true;
        $senderName = 'Anda';

        $avatarUrl = null;
        if ($user && !empty($user->foto_profil)) {
            $avatarUrl = asset('storage/' . $user->foto_profil);
        }

        return response()->json([
            'success' => true,
            'message' => [
                'id' => $message->id,
                'message' => $message->message,
                'is_me' => $isMe,
                'sender_name' => $senderName,
                'sender_initial' => strtoupper(substr($senderName, 0, 1)),
                'avatar_url' => $avatarUrl,
                'has_attachment' => $message->hasAttachment(),
                'is_image' => $message->isImage(),
                'attachment_url' => $message->hasAttachment() ? asset('storage/' . $message->attachment_path) : null,
                'attachment_name' => $message->hasAttachment() ? basename($message->attachment_path) : null,
                'formatted_time' => $message->formatted_time,
                'is_read' => $message->is_read,
                'read_at' => $message->read_at ? $message->read_at->toIso8601String() : null,
                'created_at' => $message->created_at->toIso8601String(),
            ],
        ]);
    }

    /**
     * Get messages (AJAX)
     */
    public function getMessages(Request $request, ChatRoom $chatRoom)
    {
        $user = auth()->user();

        // Validasi
        if ($chatRoom->toko_id !== $user->toko_id) {
            abort(403, 'Unauthorized');
        }

        $messages = parent::fetchMessages($chatRoom, 50);

        return response()->json([
            'success' => true,
            'messages' => $messages->items(),
            'pagination' => [
                'current_page' => $messages->currentPage(),
                'last_page' => $messages->lastPage(),
                'total' => $messages->total(),
            ],
        ]);
    }

    /**
     * Mark as read
     */
    public function markRead(ChatRoom $chatRoom)
    {
        $user = auth()->user();

        if ($chatRoom->toko_id !== $user->toko_id) {
            abort(403, 'Unauthorized');
        }

        $this->markAsRead($chatRoom, User::class, $user->id);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count helper
     */
    private function getUnreadCount($tokoId, $userId)
    {
        return ChatRoom::where('toko_id', $tokoId)
            ->active()
            ->whereHas('participants', function ($q) use ($userId) {
                $q->where('participantable_type', User::class)
                    ->where('participantable_id', $userId)
                    ->where(function ($subQ) {
                        $subQ->whereNull('last_read_at')
                            ->orWhereColumn('last_read_at', '<', 'chat_rooms.updated_at');
                    });
            })
            ->count();
    }

    /**
     * Unread count AJAX
     */
    public function unreadCount()
    {
        $user = auth()->user();
        $toko = $user->toko;

        return response()->json([
            'count' => $this->getUnreadCount($toko->id, $user->id),
        ]);
    }

    /**
     * 🔥 POLLING: Get new messages after timestamp
     */
    public function getNewMessages(Request $request, ChatRoom $chatRoom)
    {
        $user = auth()->user();
        $toko = $user->toko;

        // Validasi
        if ($chatRoom->toko_id !== $toko->id) {
            return response()->json(['success' => false], 403);
        }

        $afterTimestamp = $request->input('after');

        // ✅ Get NEW messages from customer
        $query = $chatRoom->messages()
            ->with('sender')
            ->where(function ($q) use ($user) {
                $q->where('sender_type', '!=', User::class)
                    ->orWhere('sender_id', '!=', $user->id);
            })
            ->orderBy('created_at', 'asc');

        if ($afterTimestamp) {
            $query->where('created_at', '>', $afterTimestamp);
        }

        $newMessages = $query->get();

        // Mark new messages as read
        $newMessages->each(function ($message) {
            if (!$message->is_read) {
                $message->update([
                    'is_read' => true,
                    'read_at' => now(),
                ]);
            }
        });

        // ✅ TAMBAHAN: Get pesan milik kepala toko yang statusnya berubah
        $myRecentMessages = $chatRoom->messages()
            ->where('sender_type', User::class)
            ->where('sender_id', $user->id)
            ->where('is_read', true)
            ->when($afterTimestamp, function ($q) use ($afterTimestamp) {
                $q->where(function ($subQ) use ($afterTimestamp) {
                    $subQ->where('read_at', '>', $afterTimestamp)
                        ->orWhere(function ($subSubQ) use ($afterTimestamp) {
                            $subSubQ->whereNull('read_at')
                                ->where('created_at', '>', $afterTimestamp);
                        });
                });
            })
            ->orderBy('created_at', 'asc')
            ->get();

        // Merge
        $allMessagesToReturn = $newMessages->merge($myRecentMessages)->unique('id')->sortBy('created_at');

        return response()->json([
            'success' => true,
            'messages' => $allMessagesToReturn->map(function ($message) use ($user) {
                $isMe = $message->sender_type === User::class && $message->sender_id == $user->id;
                $senderName = $isMe ? 'Anda' : ($message->sender->full_name ?? 'Customer');

                $avatarUrl = null;
                if ($message->sender && !empty($message->sender->foto_profil)) {
                    $avatarUrl = asset('storage/' . $message->sender->foto_profil);
                }

                return [
                    'id' => $message->id,
                    'message' => $message->message,
                    'is_me' => $isMe,
                    'sender_name' => $senderName,
                    'sender_initial' => strtoupper(substr($senderName, 0, 1)),
                    'avatar_url' => $avatarUrl,
                    'has_attachment' => $message->hasAttachment(),
                    'is_image' => $message->isImage(),
                    'attachment_url' => $message->hasAttachment() ? asset('storage/' . $message->attachment_path) : null,
                    'attachment_name' => $message->hasAttachment() ? basename($message->attachment_path) : null,
                    'is_read' => $message->is_read,
                    'read_at' => $message->read_at ? $message->read_at->toIso8601String() : null,
                    'formatted_time' => $message->formatted_time,
                    'created_at' => $message->created_at->toIso8601String(),
                ];
            })->values(),
        ]);
    }

    /**
     * Eskalasi ke Super Admin (Phase 6)
     */
    public function escalateToSuperAdmin(Request $request, ChatRoom $chatRoom)
    {
        $user = auth()->user();

        // Validasi
        if ($chatRoom->toko_id !== $user->toko_id) {
            abort(403, 'Unauthorized');
        }

        $request->validate([
            'reason' => 'required|string|max:500',
        ]);

        // Get super admin (ambil yang pertama aja)
        $superAdmin = User::where('role', 'super_admin')->first();

        if (!$superAdmin) {
            return response()->json([
                'success' => false,
                'message' => 'Super Admin tidak ditemukan.',
            ], 404);
        }

        // Add super admin as participant
        $this->addParticipant(
            $chatRoom,
            User::class,
            $superAdmin->id,
            \App\Models\ChatParticipant::ROLE_SUPER_ADMIN
        );

        // Send system message
        \App\Models\ChatMessage::create([
            'chat_room_id' => $chatRoom->id,
            'sender_type' => User::class,
            'sender_id' => $user->id,
            'message' => "🔔 Chat ini telah dieskalasi ke Super Admin.\nAlasan: {$request->reason}",
        ]);

        // TODO: Send notification to super admin

        return response()->json([
            'success' => true,
            'message' => 'Chat berhasil dieskalasi ke Super Admin.',
        ]);
    }
}
