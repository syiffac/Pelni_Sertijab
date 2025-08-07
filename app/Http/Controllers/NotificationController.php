<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = Notification::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('notifications.index', compact('notifications'));
    }

    // TAMBAHAN: API endpoint untuk navbar notifications
    public function getNotifications()
    {
        try {
            $notifications = Notification::where('user_id', auth()->id())
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($notification) {
                    return [
                        'id' => $notification->id,
                        'type' => $notification->type,
                        'icon' => $notification->icon,
                        'title' => $notification->title,
                        'message' => $notification->message,
                        'link' => $notification->link,
                        'read' => $notification->read,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'mutasi_id' => $notification->mutasi_id
                    ];
                });
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'notifications' => []
            ], 500);
        }
    }

    public function markAsRead(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $notification->update(['read' => true]);

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi ditandai sebagai sudah dibaca');
    }

    public function destroy(Notification $notification)
    {
        if ($notification->user_id !== auth()->id()) {
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized action');
        }

        $notification->delete();

        if (request()->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
    }

    public function markAllAsRead()
    {
        try {
            Notification::where('user_id', auth()->id())
                ->where('read', false)
                ->update(['read' => true]);

            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Semua notifikasi ditandai sebagai sudah dibaca');
        } catch (\Exception $e) {
            Log::error('Error marking all as read: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }

    public function destroyAll()
    {
        try {
            Notification::where('user_id', auth()->id())->delete();

            if (request()->ajax()) {
                return response()->json(['success' => true]);
            }

            return redirect()->back()->with('success', 'Semua notifikasi berhasil dihapus');
        } catch (\Exception $e) {
            Log::error('Error destroying all notifications: ' . $e->getMessage());
            
            if (request()->ajax()) {
                return response()->json(['success' => false, 'message' => 'Terjadi kesalahan'], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
    }
}
