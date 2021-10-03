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
        auth()->user()->notifications->markAsRead();

        return back();
    }
}
