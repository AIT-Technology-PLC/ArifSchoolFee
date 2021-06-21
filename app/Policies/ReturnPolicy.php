<?php

namespace App\Policies;

use App\Models\Returnn;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ReturnPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Return');
    }

    public function view(User $user, Returnn $returnn)
    {
        $doesReturnnBelongToMyCompany = $user->employee->company_id == $returnn->company_id;

        return $doesReturnnBelongToMyCompany && $user->can('Read Return');
    }

    public function create(User $user)
    {
        return $user->can('Create Return');
    }

    public function update(User $user, Returnn $returnn)
    {
        $doesReturnnBelongToMyCompany = $user->employee->company_id == $returnn->company_id;

        return $doesReturnnBelongToMyCompany && $user->can('Update Return');
    }

    public function delete(User $user, Returnn $returnn)
    {
        $doesReturnnBelongToMyCompany = $user->employee->company_id == $returnn->company_id;

        return $doesReturnnBelongToMyCompany && $user->can('Delete Return');
    }

    public function approve(User $user, Returnn $returnn)
    {
        $doesReturnnBelongToMyCompany = $user->employee->company_id == $returnn->company_id;

        return $doesReturnnBelongToMyCompany && $user->can('Approve Return');
    }

    public function add(User $user, Returnn $returnn)
    {
        $doesReturnnBelongToMyCompany = $user->employee->company_id == $returnn->company_id;

        return $doesReturnnBelongToMyCompany && $user->can('Add Return');
    }
}
