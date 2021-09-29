<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Notification Management');
    }

    public function unread()
    {
        return auth()->user()
            ->unreadNotifications
            ->transform(function ($notification) {
                $notification->diff_for_humans = $notification->created_at->diffForHumans();

                return $notification;
            });
    }
}
