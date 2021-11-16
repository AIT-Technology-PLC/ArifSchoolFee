<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Notification Management');
    }

    public function markAllAsRead()
    {
        if (!auth()->user()->unreadNotifications()->count()) {
            return back()->with('failedMessage', 'You do not have unseen notifications.');
        }

        auth()->user()->notifications->markAsRead();

        return back()->with('successMessage', 'You have marked all of your notifications as read.');
    }

    public function deleteAll()
    {
        if (!auth()->user()->notifications()->count()) {
            return back()->with('failedMessage', 'Your notification box is empty.');
        }

        auth()->user()->notifications()->forceDelete();

        return back()->with('successMessage', 'Deleted all notifications successfully.');
    }
}
