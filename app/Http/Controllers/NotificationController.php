<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Notification Management');
    }

    public function index()
    {
        $notifications = auth()->user()->notifications;

        $unreadNotifications = auth()->user()->unreadNotifications;

        return view('notifications.index', compact('notifications', 'unreadNotifications'));
    }

    public function update(Notification $notification)
    {
        abort_if($notification->notifiable_id != auth()->id(), 403);

        $notification->unread() ? $notification->markAsRead() : '';

        return redirect()->back();
    }

    public function markAllNotificationsAsRead()
    {
        auth()->user()->notifications->markAsRead();

        return redirect()->back();
    }
}
