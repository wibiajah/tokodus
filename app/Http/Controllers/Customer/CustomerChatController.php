<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\ChatController;
use App\Models\ChatRoom;
use App\Models\ChatParticipant;
use App\Models\Order;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;

class CustomerChatController extends ChatController
{
    /**
     * Create atau get chat room dari order (untuk "Laporkan Masalah")
     */
    public function createFromOrder(Order $order)
    {
        $customer = auth('customer')->user();

        // Validasi: order milik customer ini
        if ($order->customer_id !== $customer->id) {
            abort(403, 'Unauthorized');
        }

        // Validasi: hanya bisa chat jika sudah PAID/SHIPPED/COMPLETED
        if (!$order->canReportProblem()) {
            return redirect()->back()->with(
                'error',
                'Chat hanya tersedia setelah pembayaran dikonfirmasi. Status order saat ini: ' . $order->status_label
            );
        }

        // Get atau create chat room
        $chatRoom = $this->getOrCreateChatRoom(
            ChatRoom::TYPE_ORDER,
            $order->id,
            $order->toko_id
        );

        // Add customer as participant
        $this->addParticipant(
            $chatRoom,
            Customer::class,
            $customer->id,
            ChatParticipant::ROLE_CUSTOMER
        );

        // Add kepala toko as participant
        $kepalaToko = $order->toko->kepalaToko;
        if ($kepalaToko) {
            $this->addParticipant(
                $chatRoom,
                User::class,
                $kepalaToko->id,
                ChatParticipant::ROLE_KEPALA_TOKO
            );
        }

        return redirect()->route('customer.chat.show', $chatRoom->id);
    }

    /**
     * Show chat room
     */
    public function show(ChatRoom $chatRoom)
    {
        $customer = auth('customer')->user();

        // Validasi: customer adalah participant
        if (!$chatRoom->hasParticipant(Customer::class, $customer->id)) {
            abort(403, 'Unauthorized');
        }

        // Load relationships
        $chatRoom->load(['order.toko', 'toko', 'participants.participantable']);

        // Get messages
        $messages = $this->fetchMessages($chatRoom);

        // Mark as read
        $this->markAsRead($chatRoom, Customer::class, $customer->id);

        $chatRoom->messages()
            ->where(function ($query) use ($customer) {
                $query->where('sender_type', '!=', Customer::class)
                    ->orWhere('sender_id', '!=', $customer->id);
            })
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        return view('customer.chat.show', compact('chatRoom', 'messages'));
    }

    /**
     * Send message dengan rate limiting
     * ✅ FIX 1.2: Unified response format
     */
    public function sendMessage(Request $request, ChatRoom $chatRoom)
    {
        $customer = auth('customer')->user();

        // Validasi: customer adalah participant
        if (!$chatRoom->hasParticipant(Customer::class, $customer->id)) {
            abort(403, 'Unauthorized');
        }

        // Rate limiting: 20 messages per minute
        $key = 'chat-send:' . $customer->id;

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
        $message = $this->storeChatMessage($request, $chatRoom, Customer::class, $customer->id);

        // Hit rate limiter
        RateLimiter::hit($key, 60); // 60 seconds window

        // TODO: Broadcast event (Phase 3)

        // ✅ FIX 1.2: Format response sama seperti getNewMessages
        $isMe = true;
        $senderName = 'Anda';

        $avatarUrl = null;
        if ($customer && !empty($customer->foto_profil)) {
            $avatarUrl = asset('storage/' . $customer->foto_profil);
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
        $customer = auth('customer')->user();

        // Validasi
        if (!$chatRoom->hasParticipant(Customer::class, $customer->id)) {
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
     * Mark messages as read (AJAX)
     */
    public function markRead(ChatRoom $chatRoom)
    {
        $customer = auth('customer')->user();

        if (!$chatRoom->hasParticipant(Customer::class, $customer->id)) {
            abort(403, 'Unauthorized');
        }

        $this->markAsRead($chatRoom, Customer::class, $customer->id);

        return response()->json(['success' => true]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $customer = auth('customer')->user();

        return response()->json([
            'count' => $customer->unread_chat_count,
        ]);
    }

    /**
     * Get all chat rooms untuk customer
     */
    public function index()
    {
        $customer = auth('customer')->user();

        $chatRooms = $customer->chatRooms()
            ->with(['order', 'toko', 'latestMessage'])
            ->active()
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('customer.chat.index', compact('chatRooms'));
    }

    /**
     * 🔥 POLLING: Get new messages after timestamp
     */
   public function getNewMessages(Request $request, ChatRoom $chatRoom)
{
    $customer = auth('customer')->user();

    // Validasi
    if (!$chatRoom->hasParticipant(Customer::class, $customer->id)) {
        return response()->json(['success' => false], 403);
    }

    $afterTimestamp = $request->input('after');

    // ✅ Get NEW messages from others (yang belum di-polling sebelumnya)
    $query = $chatRoom->messages()
        ->with('sender')
        ->where(function($q) use ($customer) {
            $q->where('sender_type', '!=', Customer::class)
              ->orWhere('sender_id', '!=', $customer->id);
        })
        ->orderBy('created_at', 'asc');

    if ($afterTimestamp) {
        $query->where('created_at', '>', $afterTimestamp);
    }

    $newMessages = $query->get();

    // Mark new messages as read
    $newMessages->each(function($message) {
        if (!$message->is_read) {
            $message->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    });

    // ✅ TAMBAHAN: Get pesan milik customer yang statusnya berubah (jadi is_read = true)
    $myRecentMessages = $chatRoom->messages()
        ->where('sender_type', Customer::class)
        ->where('sender_id', $customer->id)
        ->where('is_read', true) // ← Cari yang SUDAH dibaca
        ->when($afterTimestamp, function($q) use ($afterTimestamp) {
            // Ambil pesan yang read_at-nya SETELAH last polling
            $q->where(function($subQ) use ($afterTimestamp) {
                $subQ->where('read_at', '>', $afterTimestamp)
                     ->orWhere(function($subSubQ) use ($afterTimestamp) {
                         $subSubQ->whereNull('read_at')
                                 ->where('created_at', '>', $afterTimestamp);
                     });
            });
        })
        ->orderBy('created_at', 'asc')
        ->get();

    // Merge: new messages + my messages with updated status
    $allMessagesToReturn = $newMessages->merge($myRecentMessages)->unique('id')->sortBy('created_at');

    return response()->json([
        'success' => true,
        'messages' => $allMessagesToReturn->map(function($message) use ($customer) {
            $isMe = $message->sender_type === Customer::class && $message->sender_id == $customer->id;
            $senderName = $isMe ? 'Anda' : ($message->sender->name ?? 'Admin');
            
            $avatarUrl = null;
            if ($isMe) {
                if ($customer && !empty($customer->foto_profil)) {
                    $avatarUrl = asset('storage/' . $customer->foto_profil);
                }
            } else {
                if ($message->sender && !empty($message->sender->foto_profil)) {
                    $avatarUrl = asset('storage/' . $message->sender->foto_profil);
                }
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
}
