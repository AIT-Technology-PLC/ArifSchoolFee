<?php

namespace App\Traits;

use App\Models\Employee;
use App\Notifications\GdnApproved;
use App\User;
use Illuminate\Support\Facades\Notification;

trait NotifiableUsers
{
    public function notifiableUsers($permission)
    {
        $usersFromEmployees = Employee::with('user')->companyEmployees()
            ->where('id', '<>', auth()->user()->employee->id)
            ->get()
            ->pluck('user');

        $usersId = $usersFromEmployees->pluck('id')->toArray();

        $users = User::permission($permission)->whereIn('id', $usersId)->get();

        return $users;
    }

    public function notifyCreator($resource, $notification)
    {
        if ($resource->createdBy->id != $resource->approvedBy->id) {
            Notification::send($resource->createdBy, $notification);
        }
    }
}
