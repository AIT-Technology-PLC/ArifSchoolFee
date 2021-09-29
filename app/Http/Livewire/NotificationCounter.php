<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class NotificationCounter extends Component
{
    public $totalUnreadNotifications;

    protected $listeners = [
        'notificationComponentRefreshed' => 'getTotalUnreadNotifications',
    ];

    public function render()
    {
        $this->totalUnreadNotifications = Cache::store('array')
            ->rememberForever('totalUnreadNotifications', function () {
                return auth()->user()->unreadNotifications()->count();
            });

        return view('livewire.notification-counter');
    }
}
