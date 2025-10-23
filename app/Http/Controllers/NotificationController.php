<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class NotificationController extends Controller
{
    public function index(): View
    {
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->paginate(20);

        $unreadCount = Auth::user()->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    public function markAsRead(string $id): JsonResponse
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);

        if (is_null($notification->read_at)) {
            $notification->markAsRead();
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Notification marquée comme lue'
        ]);
    }

    public function markAllAsRead(): JsonResponse|RedirectResponse
    {
        $updated = Auth::user()
            ->unreadNotifications()
            ->update(['read_at' => now()]);

        if (request()->wantsJson()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Toutes les notifications ont été marquées comme lues',
                'updated_count' => $updated
            ]);
        }

        return redirect()
            ->back()
            ->with('success', "Toutes les notifications ont été marquées comme lues");
    }

    public function destroy(string $id): JsonResponse
    {
        $notification = Auth::user()
            ->notifications()
            ->findOrFail($id);
        
        $notification->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Notification supprimée'
        ]);
    }

    public function deleteRead(): JsonResponse
    {
        $deleted = Auth::user()
            ->readNotifications()
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Notifications lues supprimées',
            'deleted_count' => $deleted
        ]);
    }

    public function getUnreadCount(): JsonResponse
    {
        $count = Auth::user()->unreadNotifications()->count();
        
        return response()->json([
            'count' => $count,
            'has_unread' => $count > 0
        ]);
    }

    public function getRecent(Request $request): JsonResponse
    {
        $limit = $request->input('limit', 5);
        
        $notifications = Auth::user()
            ->notifications()
            ->latest()
            ->take($limit)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'title' => $notification->data['title'] ?? 'Notification',
                    'message' => $notification->data['message'] ?? '',
                    'icon' => $notification->data['icon'] ?? 'fas fa-bell',
                    'type' => $notification->data['type'] ?? 'info',
                    'read_at' => $notification->read_at,
                    'created_at' => $notification->created_at->diffForHumans(),
                    'url' => $this->getNotificationUrl($notification)
                ];
            });

        return response()->json([
            'notifications' => $notifications,
            'unread_count' => Auth::user()->unreadNotifications()->count()
        ]);
    }

    private function getNotificationUrl($notification): ?string
    {
        $data = $notification->data;

        if (isset($data['stagiaire_id'])) {
            return route('stagiaires.show', $data['stagiaire_id']);
        }

        if (isset($data['url'])) {
            return $data['url'];
        }

        return null;
    }
}