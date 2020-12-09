<?php

namespace App\Policies;

use App\Customer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Customer $customer)
    {
        $doesCustomerBelongToMyCompany = $user->employee->company_id == $customer->company_id;

        $canSeeCustomer = $doesCustomerBelongToMyCompany;

        if ($canSeeCustomer) {
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

    public function update(User $user, Customer $customer)
    {
        $isUserOperative = $user->employee->permission_id == 1 ||
        $user->employee->permission_id == 2 ||
        $user->employee->permission_id == 3;

        $doesCustomerBelongToMyCompany = $user->employee->company_id == $customer->company_id;

        $canUpdateCustomer = $isUserOperative && $doesCustomerBelongToMyCompany;

        if ($canUpdateCustomer) {
            return true;
        }

        return false;
    }

    public function delete(User $user, Customer $customer)
    {
        $isUserAdmin = $user->employee->permission_id == 1 || $user->employee->permission_id == 2;

        $doesCustomerBelongToMyCompany = $user->employee->company_id == $customer->company_id;

        $canDeleteCustomer = $isUserAdmin && $doesCustomerBelongToMyCompany;

        if ($canDeleteCustomer) {
            return true;
        }

        return false;
    }
}
