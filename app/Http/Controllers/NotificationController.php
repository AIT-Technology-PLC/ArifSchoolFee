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
        if ($notification->notifiable->id == auth()->id()) {
            $notification->markAsRead();
        }

        if (request()->ajax()) {
            return response(true);
        }

        return redirect()->back();
    }

    public function getUnreadNotifications()
    {
        $unreadNotifications = auth()->user()->unreadNotifications;

        return $unreadNotifications;
    }

    public function markAllNotificationsAsRead()
    {
        auth()->user()->notifications->markAsRead();

        return redirect()->back();
    }
}
