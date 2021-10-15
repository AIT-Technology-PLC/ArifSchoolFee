<?php

namespace App\Http\Livewire;

use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class NotificationCounter extends Component
{
    public $totalUnreadNotifications, $class;

    protected $listeners = [
        'notificationComponentRefreshed' => 'getTotalUnreadNotifications',
    ];

    public function mount()
    {
        $this->totalUnreadNotifications = Cache::store('array')
            ->rememberForever(auth()->id() . '_' . 'totalUnreadNotifications', function () {
                return auth()->user()->unreadNotifications()->count();
            });
    }

    public function getTotalUnreadNotifications($value)
    {
        $this->totalUnreadNotifications = $value;
    }

    public function render()
    {
        return view('livewire.notification-counter');
    }
}
