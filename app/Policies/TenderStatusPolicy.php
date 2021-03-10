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
        return $user->can('Read Sale');
    }

    public function view(User $user, TenderStatus $tenderStatus)
    {
        $doesTenderStatusBelongToMyCompany = $user->employee->company_id == $tenderStatus->company_id;

        return $doesTenderStatusBelongToMyCompany && $user->can('Read Sale');
    }

    public function create(User $user)
    {
        return $user->can('Create Sale');
    }

    public function update(User $user, TenderStatus $tenderStatus)
    {
        $doesTenderStatusBelongToMyCompany = $user->employee->company_id == $tenderStatus->company_id;

        return $doesTenderStatusBelongToMyCompany && $user->can('Update Sale');
    }

    public function delete(User $user, TenderStatus $tenderStatus)
    {
        $doesTenderStatusBelongToMyCompany = $user->employee->company_id == $tenderStatus->company_id;

        return $doesTenderStatusBelongToMyCompany && $user->can('Delete Sale');
    }
}
