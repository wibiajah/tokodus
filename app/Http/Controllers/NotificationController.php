<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get unread notifications (untuk topbar)
     */
    public function getUnread()
    {
        try {
            $notifications = auth()->user()
                ->unreadNotifications()
                ->take(5)
                ->get()
                ->map(function($notif) {
                    // 🔥 Validasi data notification
                    $data = $notif->data;
                    
                    return [
                        'id' => $notif->id,
                        'data' => [
                            'type' => $data['type'] ?? 'unknown',
                            'message' => $data['message'] ?? 'Notifikasi baru',
                            'actor_name' => $data['actor_name'] ?? 'System', // ✅ Default jika tidak ada
                            'icon' => $data['icon'] ?? 'fas fa-info-circle',
                            'url' => $data['url'] ?? '#',
                        ],
                        'created_at' => $notif->created_at->toIso8601String(),
                        'read_at' => $notif->read_at,
                    ];
                });

            return response()->json([
                'count' => auth()->user()->unreadNotifications()->count(),
                'notifications' => $notifications,
            ]);
        } catch (\Exception $e) {
            \Log::error('Notification Error: ' . $e->getMessage());
            
            return response()->json([
                'error' => $e->getMessage(),
                'count' => 0,
                'notifications' => []
            ], 500);
        }
    }

    /**
     * Mark single notification as read
     */
    public function markAsRead($id)
    {
        try {
            $notification = auth()->user()->notifications()->findOrFail($id);
            $notification->markAsRead();

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        try {
            auth()->user()->unreadNotifications->markAsRead();
            
            return back()->with('success', 'Semua notifikasi telah ditandai sebagai dibaca');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menandai notifikasi: ' . $e->getMessage());
        }
    }

    /**
     * View all notifications page
     */
    public function index()
    {
        try {
            $notifications = auth()->user()
                ->notifications()
                ->paginate(20);

            return view('notifications.index', compact('notifications'));
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal memuat notifikasi: ' . $e->getMessage());
        }
    }
}