<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
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

        $totalUnreadNotifications = auth()->user()->unreadNotifications()->count();

        return view('notifications.index', compact('notifications', 'totalUnreadNotifications'));
    }

    public function update(Notification $notification)
    {
        abort_if($notification->notifiable_id != auth()->id(), 403);

        $notification->unread() ? $notification->markAsRead() : '';

        return back();
    }

    public function destroy(Notification $notification)
    {
        abort_if($notification->notifiable_id != auth()->id(), 403);

        $notification->forceDelete();

        return back()->with('successMessage', 'Notification deleted successfully');
    }
}
