<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    const ROLES = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'kepala_toko' => 'Kepala Toko',
        'staff_admin' => 'Staff Admin',
    ];

    // Hierarki role (dari tertinggi ke terendah)
    const ROLE_HIERARCHY = [
        'super_admin' => 4,
        'admin' => 3,
        'kepala_toko' => 2,
        'staff_admin' => 1,
    ];

    protected $fillable = [
        'name',
        'username',
        'email',
        'no_telepon',
        'password',
        'role',
        'toko_id',
        'foto_profil',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function toko()
    {
        return $this->belongsTo(Toko::class);
    }

    public function getFotoProfilUrlAttribute()
    {
        if ($this->foto_profil) {
            return Storage::url($this->foto_profil);
        }
        
        return asset('img/default-avatar.png');
    }

    public function getFormattedNoTeleponAttribute()
    {
        if (!$this->no_telepon) {
            return '-';
        }
        
        return preg_replace('/(\d{4})(\d{4})(\d+)/', '$1-$2-$3', $this->no_telepon);
    }

    // ========================================
    // 🔥 ROLE CHECKER METHODS
    // ========================================

    public function isSuperAdmin()
    {
        return $this->role === 'super_admin';
    }

    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    public function isKepalaToko()
    {
        return $this->role === 'kepala_toko';
    }

    public function isStaffAdmin()
    {
        return $this->role === 'staff_admin';
    }

    /**
     * Cek apakah user ini lebih tinggi dari role tertentu
     */
    public function isHigherThan($role)
    {
        $myLevel = self::ROLE_HIERARCHY[$this->role] ?? 0;
        $targetLevel = self::ROLE_HIERARCHY[$role] ?? 0;
        
        return $myLevel > $targetLevel;
    }

    /**
     * Cek apakah user ini lebih rendah dari role tertentu
     */
    public function isLowerThan($role)
    {
        $myLevel = self::ROLE_HIERARCHY[$this->role] ?? 0;
        $targetLevel = self::ROLE_HIERARCHY[$role] ?? 0;
        
        return $myLevel < $targetLevel;
    }

    /**
     * Get users yang harus menerima notifikasi dari user ini
     * Berdasarkan hierarki role
     */
    public function getNotificationRecipients()
    {
        $recipients = collect();

        switch ($this->role) {
            case 'staff_admin':
                // Staff -> Kepala Toko (toko yang sama) + Admin + Super Admin
                $recipients = User::where(function($query) {
                    $query->where('role', 'super_admin')
                          ->orWhere('role', 'admin')
                          ->orWhere(function($q) {
                              $q->where('role', 'kepala_toko')
                                ->where('toko_id', $this->toko_id);
                          });
                })->get();
                break;

            case 'kepala_toko':
                // Kepala Toko -> Admin + Super Admin
                $recipients = User::whereIn('role', ['admin', 'super_admin'])->get();
                break;

            case 'admin':
                // Admin -> Super Admin
                $recipients = User::where('role', 'super_admin')->get();
                break;

            case 'super_admin':
                // Super Admin -> tidak kirim notif
                $recipients = collect();
                break;
        }

        return $recipients;
    }

    /**
     * Cek apakah user ini harus menerima notifikasi dari role tertentu
     */
    public function shouldReceiveNotificationFrom($actorRole, $actorTokoId = null)
    {
        switch ($this->role) {
            case 'super_admin':
                // Super Admin menerima dari semua (Admin, Kepala Toko, Staff)
                return in_array($actorRole, ['admin', 'kepala_toko', 'staff_admin']);

            case 'admin':
                // Admin menerima dari Kepala Toko dan Staff
                return in_array($actorRole, ['kepala_toko', 'staff_admin']);

            case 'kepala_toko':
                // Kepala Toko hanya menerima dari Staff di toko yang sama
                return $actorRole === 'staff_admin' && $this->toko_id === $actorTokoId;

            case 'staff_admin':
                // Staff tidak menerima notif dari siapapun
                return false;

            default:
                return false;
        }
    }

    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }

    /**
     * Scope untuk mendapatkan user yang harus menerima notifikasi
     * dari role tertentu
     */
    public function scopeNotificationRecipientsFor($query, $actorRole, $actorTokoId = null)
    {
        switch ($actorRole) {
            case 'staff_admin':
                // Staff -> Kepala Toko (toko yang sama) + Admin + Super Admin
                return $query->where(function($q) use ($actorTokoId) {
                    $q->where('role', 'super_admin')
                      ->orWhere('role', 'admin')
                      ->orWhere(function($subQ) use ($actorTokoId) {
                          $subQ->where('role', 'kepala_toko')
                               ->where('toko_id', $actorTokoId);
                      });
                });

            case 'kepala_toko':
                // Kepala Toko -> Admin + Super Admin
                return $query->whereIn('role', ['admin', 'super_admin']);

            case 'admin':
                // Admin -> Super Admin
                return $query->where('role', 'super_admin');

            case 'super_admin':
                // Super Admin -> tidak ada
                return $query->whereRaw('1 = 0'); // Return empty

            default:
                return $query->whereRaw('1 = 0'); // Return empty
        }
    }

    // ============================================
// 🆕 CHAT RELATIONSHIP - TAMBAHKAN DI USER.PHP (SETELAH scopeNotificationRecipientsFor())
// ============================================

/**
 * Relasi ke chat participants (as user - kepala toko / super admin)
 */
public function chatParticipants()
{
    return $this->morphMany(ChatParticipant::class, 'participantable');
}

/**
 * Relasi ke chat messages (as sender)
 */
public function chatMessages()
{
    return $this->morphMany(ChatMessage::class, 'sender');
}

/**
 * Get all chat rooms user ini
 */
public function chatRooms()
{
    return $this->morphToMany(ChatRoom::class, 'participantable', 'chat_participants')
        ->withPivot('role', 'last_read_at', 'joined_at')
        ->withTimestamps();
}

/**
 * Get active chat rooms
 */
public function activeChatRooms()
{
    return $this->chatRooms()->where('status', 'active');
}

/**
 * Get total unread messages count
 */
public function getUnreadChatCountAttribute()
{
    $count = 0;
    foreach ($this->activeChatRooms as $room) {
        $count += $room->getUnreadCount(User::class, $this->id);
    }
    return $count;
}
}