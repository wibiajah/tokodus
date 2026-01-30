<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ChatMessage extends Model
{
    use HasFactory;

    const ATTACHMENT_IMAGE = 'image';
    const ATTACHMENT_FILE = 'file';

    protected $fillable = [
        'chat_room_id',
        'sender_id',
        'sender_type',
        'message',
        'attachment_path',
        'attachment_type',
        'is_read',
        'read_at',
    ];

    protected $casts = [
        'is_read' => 'boolean',
        'read_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // ========== RELATIONSHIPS ==========
    public function chatRoom()
    {
        return $this->belongsTo(ChatRoom::class);
    }

    public function sender()
    {
        return $this->morphTo();
    }

    // ========== ACCESSORS ==========
    public function getAttachmentUrlAttribute()
    {
        if (!$this->attachment_path) {
            return null;
        }

        return Storage::url($this->attachment_path);
    }

    public function getFormattedTimeAttribute()
    {
        return $this->created_at->format('H:i');
    }

    public function getFormattedDateAttribute()
    {
        return $this->created_at->format('d M Y');
    }

    // ========== HELPERS ==========
    public function hasAttachment()
    {
        return !empty($this->attachment_path);
    }

    public function isImage()
    {
        return $this->attachment_type === self::ATTACHMENT_IMAGE;
    }

    public function isFile()
    {
        return $this->attachment_type === self::ATTACHMENT_FILE;
    }

    public function markAsRead()
    {
        if (!$this->is_read) {
            $this->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
        }
    }

    public function isSentBy($senderType, $senderId)
    {
        return $this->sender_type === $senderType && $this->sender_id == $senderId;
    }

    // ========== SCOPES ==========
    public function scopeUnread($query)
    {
        return $query->where('is_read', false);
    }

    public function scopeRead($query)
    {
        return $query->where('is_read', true);
    }

    public function scopeWithAttachment($query)
    {
        return $query->whereNotNull('attachment_path');
    }
}