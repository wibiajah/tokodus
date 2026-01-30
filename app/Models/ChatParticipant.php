<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatParticipant extends Model
{
    use HasFactory;

    const ROLE_CUSTOMER = 'customer';
    const ROLE_KEPALA_TOKO = 'kepala_toko';
    const ROLE_SUPER_ADMIN = 'super_admin';

    protected $fillable = [
        'chat_room_id',
        'participantable_id',
        'participantable_type',
        'role',
        'last_read_at',
        'joined_at',
    ];

    protected $casts = [
        'last_read_at' => 'datetime',
        'joined_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function participantable()
    {
        return $this->morphTo();
    }

    // ========== HELPERS ==========
    public function isCustomer()
    {
        return $this->role === self::ROLE_CUSTOMER;
    }

    public function isKepalaToko()
    {
        return $this->role === self::ROLE_KEPALA_TOKO;
    }

    public function isSuperAdmin()
    {
        return $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function markAsRead()
    {
        $this->update(['last_read_at' => now()]);
    }

    public function getUnreadCount()
    {
        if (!$this->last_read_at) {
            return $this->chatRoom->messages()->count();
        }

        return $this->chatRoom->messages()
            ->where('created_at', '>', $this->last_read_at)
            ->where(function($query) {
                $query->where('sender_type', '!=', $this->participantable_type)
                      ->orWhere('sender_id', '!=', $this->participantable_id);
            })
            ->count();
    }
}