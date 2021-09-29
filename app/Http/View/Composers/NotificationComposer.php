<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view)
    {
        if (!auth()->check()) {
            return false;
        }

        $view->with([
            'unreadNotificationsCount' => auth()->user()->unreadNotifications()->count(),
        ]);
    }
}
