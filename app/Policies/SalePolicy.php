<?php

namespace App\Policies;

use App\Models\Sale;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class SalePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Sale $sale)
    {
        $doesSaleBelongToMyCompany = $user->employee->company_id == $sale->company_id;

        $canSeeSale = $doesSaleBelongToMyCompany;

        if ($canSeeSale) {
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

    public function update(User $user, Sale $sale)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesSaleBelongToMyCompany = $user->employee->company_id == $sale->company_id;

        $canUpdateSale = $isUserOperative && $doesSaleBelongToMyCompany;

        if ($canUpdateSale) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Sale $sale)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesSaleBelongToMyCompany = $user->employee->company_id == $sale->company_id;

        $canDeleteSale = $isUserAdmin && $doesSaleBelongToMyCompany;

        if ($canDeleteSale) {
            return true;
        }

        return false;
    }
}
