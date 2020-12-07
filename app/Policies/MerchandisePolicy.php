<?php

namespace App\Policies;

use App\Models\Merchandise;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MerchandisePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Merchandise $merchandise)
    {
        $isUserOperative = true;

        $doesMerchandiseBelongToMyCompany = $user->employee->company_id == $merchandise->company_id;

        $canSeeMerchandise = $isUserOperative && $doesMerchandiseBelongToMyCompany;

        if ($canSeeMerchandise) {
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

    public function update(User $user, Merchandise $merchandise)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesMerchandiseBelongToMyCompany = $user->employee->company_id == $merchandise->company_id;

        $canUpdateMerchandise = $isUserOperative && $doesMerchandiseBelongToMyCompany;

        if ($canUpdateMerchandise) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Merchandise $merchandise)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesMerchandiseBelongToMyCompany = $user->employee->company_id == $merchandise->company_id;

        $canDeleteMerchandise = $isUserAdmin && $doesMerchandiseBelongToMyCompany;

        if ($canDeleteMerchandise) {
            return true;
        }

        return false;
    }
}
