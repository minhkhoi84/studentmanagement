<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Lấy danh sách notifications cho admin đang đăng nhập
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();
        
        // Chỉ admin mới có quyền xem notifications
        if ($user->role !== 'super_admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $notifications = Notification::forAdmin($user->id)
            ->orderBy('created_at', 'desc')
            ->limit(50) // Giới hạn 50 thông báo gần nhất
            ->get();

        $unreadCount = Notification::forAdmin($user->id)
            ->unread()
            ->count();

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => $unreadCount,
        ]);
    }

    /**
     * Đánh dấu 1 notification là đã đọc
     */
    public function markAsRead(Request $request, $id): JsonResponse
    {
        $user = $request->user();
        
        if ($user->role !== 'super_admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        $notification = Notification::forAdmin($user->id)->find($id);

        if (!$notification) {
            return response()->json([
                'message' => 'Notification not found'
            ], 404);
        }

        $notification->markAsRead();

        return response()->json([
            'message' => 'Notification marked as read',
            'notification' => $notification,
        ]);
    }

    /**
     * Đánh dấu tất cả notifications là đã đọc
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        $user = $request->user();
        
        if ($user->role !== 'super_admin') {
            return response()->json([
                'message' => 'Unauthorized'
            ], 403);
        }

        Notification::forAdmin($user->id)
            ->unread()
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'message' => 'All notifications marked as read',
        ]);
    }
}
