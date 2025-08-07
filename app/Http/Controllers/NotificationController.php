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

    // API endpoint untuk navbar notifications
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
            
            $unreadCount = Notification::where('user_id', auth()->id())
                ->where('read', false)
                ->count();
            
            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);
        } catch (\Exception $e) {
            Log::error('Error fetching notifications: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'notifications' => [],
                'unread_count' => 0
            ], 500);
        }
    }

    // PERBAIKAN: Method destroy individual notification
    public function destroy($id)
    {
        try {
            // DEBUGGING: Log request details
            Log::info('Destroy notification called with ID: ' . $id);
            Log::info('Request method: ' . request()->method());
            Log::info('User ID: ' . auth()->id());
            
            // Find notification
            $notification = Notification::find($id);
            
            if (!$notification) {
                Log::warning('Notification not found with ID: ' . $id);
                
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Notifikasi tidak ditemukan'
                    ], 404);
                }
                return redirect()->back()->with('error', 'Notifikasi tidak ditemukan');
            }

            // Check ownership
            if ($notification->user_id !== auth()->id()) {
                Log::warning('Unauthorized access attempt for notification ID: ' . $id . ' by user: ' . auth()->id());
                
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Unauthorized'
                    ], 403);
                }
                return redirect()->back()->with('error', 'Unauthorized action');
            }

            // Delete notification
            $notification->delete();
            
            Log::info('Notification deleted successfully - ID: ' . $id . ' by user: ' . auth()->id());

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Notifikasi berhasil dihapus'
                ]);
            }

            return redirect()->back()->with('success', 'Notifikasi berhasil dihapus');
            
        } catch (\Exception $e) {
            Log::error('Error deleting notification: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus notifikasi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus notifikasi');
        }
    }

    // PERBAIKAN: Method destroy all notifications
    public function destroyAll()
    {
        try {
            $userId = auth()->id();
            
            // DEBUGGING: Log request details
            Log::info('Destroy all notifications called by user: ' . $userId);
            Log::info('Request method: ' . request()->method());
            
            // Count notifications before deletion
            $count = Notification::where('user_id', $userId)->count();
            
            Log::info('Found ' . $count . ' notifications to delete for user: ' . $userId);
            
            if ($count === 0) {
                if (request()->ajax()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Tidak ada notifikasi untuk dihapus'
                    ]);
                }
                return redirect()->back()->with('info', 'Tidak ada notifikasi untuk dihapus');
            }
            
            // Delete all notifications for current user
            $deletedCount = Notification::where('user_id', $userId)->delete();

            Log::info("Successfully deleted {$deletedCount} notifications for user {$userId}");

            if (request()->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => "Berhasil menghapus {$deletedCount} notifikasi",
                    'deleted_count' => $deletedCount
                ]);
            }

            return redirect()->back()->with('success', "Berhasil menghapus {$deletedCount} notifikasi");
            
        } catch (\Exception $e) {
            Log::error('Error destroying all notifications: ' . $e->getMessage());
            Log::error('Stack trace: ' . $e->getTraceAsString());
            
            if (request()->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menghapus semua notifikasi: ' . $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus semua notifikasi');
        }
    }
}
