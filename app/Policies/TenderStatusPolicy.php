<?php

namespace App\Policies;

use App\Models\TenderStatus;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class TenderStatusPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Tender');
    }

    public function view(User $user, TenderStatus $tenderStatus)
    {
        $doesTenderStatusBelongToMyCompany = $user->employee->company_id == $tenderStatus->company_id;

        return $doesTenderStatusBelongToMyCompany && $user->can('Read Tender');
    }

    public function create(User $user)
    {
        return $user->can('Create Tender');
    }

    public function update(User $user, TenderStatus $tenderStatus)
    {
        $doesTenderStatusBelongToMyCompany = $user->employee->company_id == $tenderStatus->company_id;

        return $doesTenderStatusBelongToMyCompany && $user->can('Update Tender');
    }

    public function delete(User $user, TenderStatus $tenderStatus)
    {
        $doesTenderStatusBelongToMyCompany = $user->employee->company_id == $tenderStatus->company_id;

        return $doesTenderStatusBelongToMyCompany && $user->can('Delete Tender');
    }
}
