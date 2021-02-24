<?php

namespace App\Policies;

use App\Models\Gdn;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class GdnPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read GDN');
    }

    public function view(User $user, Gdn $gdn)
    {
        $doesGdnBelongToMyCompany = $user->employee->company_id == $gdn->company_id;

        return $doesGdnBelongToMyCompany && $user->can('Read GDN');
    }

    public function create(User $user)
    {
        return $user->can('Create GDN');
    }

    public function update(User $user, Gdn $gdn)
    {
        $doesGdnBelongToMyCompany = $user->employee->company_id == $gdn->company_id;

        return $doesGdnBelongToMyCompany && $user->can('Update GDN');
    }

    public function delete(User $user, Gdn $gdn)
    {
        $doesGdnBelongToMyCompany = $user->employee->company_id == $gdn->company_id;

        return $doesGdnBelongToMyCompany && $user->can('Delete GDN');
    }

    public function approve(User $user, Gdn $gdn)
    {
        $doesGdnBelongToMyCompany = $user->employee->company_id == $gdn->company_id;

        return $doesGdnBelongToMyCompany && $user->can('Approve GDN');
    }

    public function subtract(User $user, Gdn $gdn)
    {
        $doesGdnBelongToMyCompany = $user->employee->company_id == $gdn->company_id;

        return $doesGdnBelongToMyCompany && $user->can('Subtract GDN');
    }
}
