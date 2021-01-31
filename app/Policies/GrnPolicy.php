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
        return true;
    }

    public function view(User $user, Grn $grn)
    {
        $doesGrnBelongToMyCompany = $user->employee->company_id == $grn->company_id;

        $canSeeGrn = $doesGrnBelongToMyCompany;

        if ($canSeeGrn) {
            return true;
        }

        return false;
    }

    public function create(User $user)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        return $isUserOperative;
    }

    public function update(User $user, Grn $grn)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesGrnBelongToMyCompany = $user->employee->company_id == $grn->company_id;

        $canUpdateGrn = $isUserOperative && $doesGrnBelongToMyCompany;

        if ($canUpdateGrn) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Grn $grn)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesGrnBelongToMyCompany = $user->employee->company_id == $grn->company_id;

        $canDeleteGrn = $isUserAdmin && $doesGrnBelongToMyCompany;

        if ($canDeleteGrn) {
            return true;
        }

        return false;
    }
}
