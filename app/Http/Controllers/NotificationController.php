<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(Request $request)
    {
        $notifications = $request->user()
            ->notifications()
            ->latest()
            ->limit(50)
            ->get()
            ->map(function ($notification) {
                return [
                    'id' => $notification->id,
                    'type' => $notification->data['type'] ?? 'info',
                    'message' => $notification->data['message'] ?? 'New notification',
                    'post_id' => $notification->data['post_id'] ?? null,
                    'created_at' => $notification->created_at,
                    'read_at' => $notification->read_at,
                ];
            });

        return response()->json($notifications);
    }
}
