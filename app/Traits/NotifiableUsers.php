<?php

namespace App\Traits;

use App\User;

trait NotifiableUsers
{
    public function notifiableUsers($permission, $creator = null)
    {
        if (auth()->user()->can($permission) && auth()->user()->is($creator)) {
            return [];
        }

        if (auth()->user()->can($permission) && auth()->user()->isNot($creator)) {
            return $creator ?? [];
        }

        $users = User::permission($permission)
            ->whereIn('id', function ($query) {
                $query->select('user_id')
                    ->from('employees')
                    ->where('company_id', userCompany()->id)
                    ->where('id', '<>', auth()->user()->employee->id);
            })->get();

        if ($users->contains('warehouse_id', auth()->user()->warehouse_id)) {
            $users = $users->where('warehouse_id', auth()->user()->warehouse_id);
        }

        if ($creator) {
            $users->push($creator);
            $users = $users->unique();
        }

        return $users->except(auth()->id());
    }
}
