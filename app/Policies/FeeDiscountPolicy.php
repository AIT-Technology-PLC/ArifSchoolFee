<?php

namespace App\Policies;

use App\Models\FeeDiscount;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class FeeDiscountPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Fee Discount');
    }

    public function view(User $user, FeeDiscount $feeDiscount)
    {
        return $user->can('Read Fee Discount');
    }

    public function create(User $user)
    {
        return $user->can('Create Fee Discount');
    }

    public function update(User $user, FeeDiscount $feeDiscount)
    {
        return $user->can('Update Fee Discount');
    }

    public function delete(User $user, FeeDiscount $feeDiscount)
    {
        return $user->can('Delete Fee Discount');
    }
}
