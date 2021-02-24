<?php

namespace App\Policies;

use App\Models\Grn;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GrnPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read GRN');
    }

    public function view(User $user, Grn $grn)
    {
        $doesGrnBelongToMyCompany = $user->employee->company_id == $grn->company_id;

        return $doesGrnBelongToMyCompany && $user->can('Read GRN');
    }

    public function create(User $user)
    {
        return $user->can('Create GRN');
    }

    public function update(User $user, Grn $grn)
    {
        $doesGrnBelongToMyCompany = $user->employee->company_id == $grn->company_id;

        return $doesGrnBelongToMyCompany && $user->can('Update GRN');
    }

    public function delete(User $user, Grn $grn)
    {
        $doesGrnBelongToMyCompany = $user->employee->company_id == $grn->company_id;

        return $doesGrnBelongToMyCompany && $user->can('Delete GRN');
    }

    public function approve(User $user, Grn $grn)
    {
        $doesGrnBelongToMyCompany = $user->employee->company_id == $grn->company_id;

        return $doesGrnBelongToMyCompany && $user->can('Approve GRN');
    }
}
