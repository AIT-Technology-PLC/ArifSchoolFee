<?php

namespace App\Policies;

use App\Models\Gdn;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GdnPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Gdn $gdn)
    {
        $doesGdnBelongToMyCompany = $user->employee->company_id == $gdn->company_id;

        $canSeeGdn = $doesGdnBelongToMyCompany;

        if ($canSeeGdn) {
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

    public function update(User $user, Gdn $gdn)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesGdnBelongToMyCompany = $user->employee->company_id == $gdn->company_id;

        $canUpdateGdn = $isUserOperative && $doesGdnBelongToMyCompany;

        if ($canUpdateGdn) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Gdn $gdn)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesGdnBelongToMyCompany = $user->employee->company_id == $gdn->company_id;

        $canDeleteGdn = $isUserAdmin && $doesGdnBelongToMyCompany;

        if ($canDeleteGdn) {
            return true;
        }

        return false;
    }
}
