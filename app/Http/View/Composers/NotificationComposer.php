<?php

namespace App\Http\View\Composers;

use Illuminate\View\View;

class NotificationComposer
{
    public function compose(View $view)
    {
        if (auth()->check()) {
            $view->with([
                'unreadNotifications' => auth()->user()->unreadNotifications,
                'readNotifications' => auth()->user()->readNotifications,
            ]);
        }
    }
}
