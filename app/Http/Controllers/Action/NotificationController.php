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
        if (!authUser()->unreadNotifications()->count()) {
            return back()->with('failedMessage', 'You do not have unseen notifications.');
        }

        authUser()->notifications->markAsRead();

        return back()->with('successMessage', 'You have marked all of your notifications as read.');
    }

    public function deleteAll()
    {
        if (!authUser()->notifications()->count()) {
            return back()->with('failedMessage', 'Your notification box is empty.');
        }

        authUser()->notifications()->forceDelete();

        return back()->with('successMessage', 'Deleted all notifications successfully.');
    }
}
