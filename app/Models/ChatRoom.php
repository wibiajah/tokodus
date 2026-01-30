<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRoom extends Model
{
    use HasFactory;

    const TYPE_ORDER = 'order_chat';
    const TYPE_GENERAL = 'general_chat';

    const STATUS_ACTIVE = 'active';
    const STATUS_CLOSED = 'closed';

    protected $fillable = [
        'type',
        'order_id',
        'toko_id',
        'status',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function participants()
    {
        return $this->hasMany(ChatParticipant::class);
    }

    public function messages()
    {
        return $this->hasMany(ChatMessage::class)->orderBy('created_at', 'asc');
    }

    public function latestMessage()
    {
        return $this->hasOne(ChatMessage::class)->latestOfMany();
    }

    // ========== HELPERS ==========
    public function isOrderChat()
    {
        return $this->type === self::TYPE_ORDER;
    }

    public function isGeneralChat()
    {
        return $this->type === self::TYPE_GENERAL;
    }

    public function isActive()
    {
        return $this->status === self::STATUS_ACTIVE;
    }

    public function isClosed()
    {
        return $this->status === self::STATUS_CLOSED;
    }

    // Get unread count untuk participant tertentu
    public function getUnreadCount($participantableType, $participantableId)
    {
        $participant = $this->participants()
            ->where('participantable_type', $participantableType)
            ->where('participantable_id', $participantableId)
            ->first();

        if (!$participant || !$participant->last_read_at) {
            return $this->messages()->count();
        }

        return $this->messages()
            ->where('created_at', '>', $participant->last_read_at)
            ->where(function($query) use ($participantableType, $participantableId) {
                $query->where('sender_type', '!=', $participantableType)
                      ->orWhere('sender_id', '!=', $participantableId);
            })
            ->count();
    }

    // Check if user is participant
    public function hasParticipant($participantableType, $participantableId)
    {
        return $this->participants()
            ->where('participantable_type', $participantableType)
            ->where('participantable_id', $participantableId)
            ->exists();
    }

    // ========== SCOPES ==========
    public function scopeActive($query)
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeClosed($query)
    {
        return $query->where('status', self::STATUS_CLOSED);
    }

    public function scopeOrderChat($query)
    {
        return $query->where('type', self::TYPE_ORDER);
    }

    public function scopeGeneralChat($query)
    {
        return $query->where('type', self::TYPE_GENERAL);
    }

    public function scopeForParticipant($query, $participantableType, $participantableId)
    {
        return $query->whereHas('participants', function($q) use ($participantableType, $participantableId) {
            $q->where('participantable_type', $participantableType)
              ->where('participantable_id', $participantableId);
        });
    }
}