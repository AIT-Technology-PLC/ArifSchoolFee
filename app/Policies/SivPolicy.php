<?php

namespace App\Policies;

use App\Models\Models\Siv;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SivPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read SIV');
    }

    public function view(User $user, Siv $siv)
    {
        $doesSivBelongToMyCompany = $user->employee->company_id == $siv->company_id;

        return $doesSivBelongToMyCompany && $user->can('Read SIV');
    }

    public function create(User $user)
    {
        return $user->can('Create SIV');
    }

    public function update(User $user, Siv $siv)
    {
        $doesSivBelongToMyCompany = $user->employee->company_id == $siv->company_id;

        return $doesSivBelongToMyCompany && $user->can('Update SIV');
    }

    public function delete(User $user, Siv $siv)
    {
        $doesSivBelongToMyCompany = $user->employee->company_id == $siv->company_id;

        return $doesSivBelongToMyCompany && $user->can('Delete SIV');
    }

    public function approve(User $user, Siv $siv)
    {
        $doesSivBelongToMyCompany = $user->employee->company_id == $siv->company_id;

        return $doesSivBelongToMyCompany && $user->can('Approve SIV');
    }
}
