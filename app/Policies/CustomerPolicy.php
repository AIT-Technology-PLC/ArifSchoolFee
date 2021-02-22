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
        return $user->can('Read Sale') ? true : false;
    }

    public function view(User $user, Customer $customer)
    {
        $doesCustomerBelongToMyCompany = $user->employee->company_id == $customer->company_id;

        return $doesCustomerBelongToMyCompany && $user->can('Read Sale') ?
        true : false;
    }

    public function create(User $user)
    {
        return $user->can('Create Sale');
    }

    public function update(User $user, Customer $customer)
    {
        $doesCustomerBelongToMyCompany = $user->employee->company_id == $customer->company_id;

        return $user->can('Update Sale') && $doesCustomerBelongToMyCompany
        ? true : false;

    }

    public function delete(User $user, Customer $customer)
    {
        $doesCustomerBelongToMyCompany = $user->employee->company_id == $customer->company_id;

        return $user->can('Delete Sale') && $doesCustomerBelongToMyCompany;
    }
}
