<?php

namespace App\Policies;

use App\Models\Tender;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenderPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Sale');
    }

    public function view(User $user, Tender $tender)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $tender->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Read Sale');
    }

    public function create(User $user)
    {
        return $user->can('Create Sale');
    }

    public function update(User $user, Tender $tender)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $tender->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Update Sale');
    }

    public function delete(User $user, Tender $tender)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $tender->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Delete Sale');
    }
}
