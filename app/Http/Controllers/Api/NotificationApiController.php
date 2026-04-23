<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationApiController extends Controller
{
    // ─────────────────────────────────────────────────────────────────────────
    //  GET /api/v1/notifications?page=1
    //  Miroir exact de NotificationController::index() web
    // ─────────────────────────────────────────────────────────────────────────

    public function index(Request $request)
    {
        $user          = Auth::user();
        $notifications = $user->notifications()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return response()->json([
            'success'      => true,
            'data'         => $notifications->map(fn ($n) => $this->formatNotification($n)),
            'unread_count' => $user->unreadNotificationsCount(),
            'meta'         => [
                'total'        => $notifications->total(),
                'current_page' => $notifications->currentPage(),
                'last_page'    => $notifications->lastPage(),
                'per_page'     => $notifications->perPage(),
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  GET /api/v1/notifications/unread-count
    // ─────────────────────────────────────────────────────────────────────────

    public function unreadCount()
    {
        return response()->json([
            'success'      => true,
            'unread_count' => Auth::user()->unreadNotificationsCount(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  PUT /api/v1/notifications/{id}/read
    //  Miroir de NotificationController::markAsRead() web
    // ─────────────────────────────────────────────────────────────────────────

    public function markAsRead($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->markAsRead();

        return response()->json([
            'success'      => true,
            'unread_count' => Auth::user()->unreadNotificationsCount(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  PUT /api/v1/notifications/read-all
    //  Miroir de NotificationController::markAllAsRead() web
    // ─────────────────────────────────────────────────────────────────────────

    public function markAllAsRead()
    {
        $user = Auth::user();
        $user->notifications()
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);

        return response()->json([
            'success'      => true,
            'unread_count' => 0,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  DELETE /api/v1/notifications/{id}
    //  Miroir de NotificationController::destroy() web
    // ─────────────────────────────────────────────────────────────────────────

    public function destroy($id)
    {
        $notification = Notification::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $notification->delete();

        return response()->json([
            'success'      => true,
            'unread_count' => Auth::user()->unreadNotificationsCount(),
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    //  Helper — formater une notification pour Flutter
    // ─────────────────────────────────────────────────────────────────────────

    private function formatNotification(Notification $n): array
    {
        return [
            'id'         => $n->id,
            'type'       => $n->type,
            'title'      => $n->title,
            'message'    => $n->message,
            'is_read'    => (bool) $n->is_read,
            'read_at'    => $n->read_at?->toIso8601String(),
            'data'       => $n->data,
            'created_at' => $n->created_at->toIso8601String(),
        ];
    }
}
