<?php

namespace App\Http\Controllers;

use Illuminate\Notifications\DatabaseNotification as Notification;

class NotificationController extends Controller
{
    public function index()
    {
        $notifications = auth()->user()->notifications;

        return view('notifications.index', compact('notifications'));
    }

    public function getReadNotifications()
    {
        $readNotifications = auth()->user()->readNotifications;

        return $readNotifications;
    }

    public function getUnreadNotifications()
    {
        $unreadNotifications = auth()->user()->unreadNotifications;

        return $unreadNotifications;
    }

    public function markNotificationAsRead(Notification $notification)
    {
        $notification->markAsRead();
    }

    public function destroy(Notification $notification)
    {
        $notification->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
