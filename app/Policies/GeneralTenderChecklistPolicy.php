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
        return $user->can('Read Tender');
    }

    public function view(User $user, GeneralTenderChecklist $generalTenderChecklist)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $generalTenderChecklist->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Read Tender');
    }

    public function create(User $user)
    {
        return $user->can('Create Tender');
    }

    public function update(User $user, GeneralTenderChecklist $generalTenderChecklist)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $generalTenderChecklist->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Update Tender');
    }

    public function delete(User $user, GeneralTenderChecklist $generalTenderChecklist)
    {
        $doesTenderBelongToMyCompany = $user->employee->company_id == $generalTenderChecklist->company_id;

        return $doesTenderBelongToMyCompany && $user->can('Delete Tender');
    }
}
