<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Traits\ApiResponse;

class NotificationController extends Controller
{
    use ApiResponse;
    public function index()
    {
        $user = auth()->user();

        return $this->success("Success", "notifications", $user->notifications()->latest()->get());
    }

    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->findOrFail($id);

        $notification->markAsRead();

        return $this->success("Success", "message", "Notification marked as read");
    }

    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return $this->success("Success", "message", "All notifications marked as read");
    }
}
