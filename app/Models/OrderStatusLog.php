<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatusLog extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'order_id',
        'status_from',
        'status_to',
        'changed_by',
        'notes',
        'created_at',
    ];

    protected $casts = [
        'created_at' => 'datetime',
    ];

    // ============================================
    // RELATIONSHIPS
    // ============================================

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }

    // ============================================
    // ACCESSORS
    // ============================================

    public function getStatusFromTextAttribute()
    {
        return $this->formatStatus($this->status_from);
    }

    public function getStatusToTextAttribute()
    {
        return $this->formatStatus($this->status_to);
    }

    public function getChangedByNameAttribute()
    {
        return $this->changedBy ? $this->changedBy->name : 'System';
    }

    // ============================================
    // HELPERS
    // ============================================

    private function formatStatus($status)
    {
        $statuses = [
            'pending' => 'Menunggu',
            'processing' => 'Diproses',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];

        return $statuses[$status] ?? $status;
    }

    public function getFormattedCreatedAtAttribute()
    {
        return $this->created_at->format('d M Y, H:i');
    }
}