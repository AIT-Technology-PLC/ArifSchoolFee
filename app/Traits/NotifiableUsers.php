<?php

namespace App\Traits;

use App\User;

trait NotifiableUsers
{
    public function notifiableUsers($permission, $creator = null)
    {
        $users = User::permission($permission)
            ->whereIn('id', function ($query) {
                $query->select('user_id')
                    ->from('employees')
                    ->where('company_id', userCompany()->id)
                    ->where('id', '<>', auth()->user()->employee->id);
            })->get();

        if ($creator) {
            $users->push($creator);
            $users = $users->unique();
        }

        return $users->except(auth()->id());
    }
}
