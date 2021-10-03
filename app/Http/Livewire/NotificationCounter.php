<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class NotificationCounter extends Component
{
    public $totalUnreadNotifications, $class;

    protected $listeners = [
        'notificationComponentRefreshed' => 'render',
    ];

    public function render()
    {
        $this->totalUnreadNotifications = Cache::store('array')
            ->rememberForever(auth()->id() . '_' . 'totalUnreadNotifications', function () {
                return auth()->user()->unreadNotifications()->count();
            });

        return view('livewire.notification-counter');
    }
}
