<?php

namespace App\Policies;

use App\Models\GeneralTenderChecklist;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GeneralTenderChecklistPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Sale');
    }

    public function view(User $user, GeneralTenderChecklist $generalTenderChecklist)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $generalTenderChecklist->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Read Sale');
    }

    public function create(User $user)
    {
        return $user->can('Create Sale');
    }

    public function update(User $user, GeneralTenderChecklist $generalTenderChecklist)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $generalTenderChecklist->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Update Sale');
    }

    public function delete(User $user, GeneralTenderChecklist $generalTenderChecklist)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $generalTenderChecklist->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Delete Sale');
    }
}
