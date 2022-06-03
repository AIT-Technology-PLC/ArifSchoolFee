<?php

namespace App\Policies;

use App\Models\BillOfMaterial;
use App\Models\User;
use App\Traits\VerifyModelIssuer;
use Illuminate\Auth\Access\HandlesAuthorization;

class BillOfMaterialPolicy
{
    use HandlesAuthorization, VerifyModelIssuer;

    public function viewAny(User $user)
    {
        return $user->can('Read BOM');
    }

    public function view(User $user, BillOfMaterial $billOfMaterial)
    {
        return $user->can('Read BOM');
    }

    public function create(User $user)
    {
        return $user->can('Create BOM');
    }

    public function update(User $user)
    {
        return $user->can('Update BOM');
    }

    public function delete(User $user)
    {
        return $user->can('Delete BOM');
    }
}