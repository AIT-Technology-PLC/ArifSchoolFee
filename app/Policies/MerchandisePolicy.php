<?php

namespace App\Policies;

use App\Models\Merchandise;
use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class MerchandisePolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Merchandise');
    }

    public function view(User $user, Merchandise $merchandise)
    {
        $doesMerchandiseBelongToMyCompany = $user->employee->company_id == $merchandise->company_id;

        return $doesMerchandiseBelongToMyCompany && $user->can('Read Merchandise');
    }

    public function create(User $user)
    {
        return $user->can('Create Merchandise');
    }

    public function update(User $user, Merchandise $merchandise)
    {
        $doesMerchandiseBelongToMyCompany = $user->employee->company_id == $merchandise->company_id;

        return $doesMerchandiseBelongToMyCompany && $user->can('Update Merchandise');
    }

    public function delete(User $user, Merchandise $merchandise)
    {
        $doesMerchandiseBelongToMyCompany = $user->employee->company_id == $merchandise->company_id;

        return $doesMerchandiseBelongToMyCompany && $user->can('Delete Merchandise');
    }
}
