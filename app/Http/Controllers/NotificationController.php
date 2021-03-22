<?php

namespace App\Http\Controllers;

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

    public function markNotificationAsRead($notification)
    {
        $notification = auth()->user()->notifications->where('id', $notification)->first();

        $notification->markAsRead();
    }

    public function destroy($notification)
    {
        $notification = auth()->user()->notifications->where('id', $notification)->first();

        $notification->delete();
    }
}
