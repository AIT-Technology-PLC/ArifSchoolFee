<?php

namespace App\Policies;

use App\Models\Customer;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CustomerPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Customer') ? true : false;
    }

    public function view(User $user, Customer $customer)
    {
        $doesCustomerBelongToMyCompany = $user->employee->company_id == $customer->company_id;

        return $doesCustomerBelongToMyCompany && $user->can('Read Customer') ?
        true : false;
    }

    public function create(User $user)
    {
        return $user->can('Create Customer');
    }

    public function update(User $user, Customer $customer)
    {
        $doesCustomerBelongToMyCompany = $user->employee->company_id == $customer->company_id;

        return $user->can('Update Customer') && $doesCustomerBelongToMyCompany
        ? true : false;

    }

    public function delete(User $user, Customer $customer)
    {
        $doesCustomerBelongToMyCompany = $user->employee->company_id == $customer->company_id;

        return $user->can('Delete Customer') && $doesCustomerBelongToMyCompany;
    }
}
