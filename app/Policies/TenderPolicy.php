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
        return $user->can('Read Tender');
    }

    public function view(User $user, Tender $tender)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $tender->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Read Tender');
    }

    public function create(User $user)
    {
        return $user->can('Create Tender');
    }

    public function update(User $user, Tender $tender)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $tender->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Update Tender');
    }

    public function delete(User $user, Tender $tender)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $tender->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Delete Tender');
    }
}
