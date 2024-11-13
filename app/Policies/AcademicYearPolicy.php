<?php

namespace App\Policies;

use App\Models\AcademicYear;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AcademicYearPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Academic Year');
    }

    public function view(User $user, AcademicYear $academicYear)
    {
        return $user->can('Read Academic Year');
    }

    public function create(User $user)
    {
        return $user->can('Create Academic Year');
    }

    public function update(User $user, AcademicYear $academicYear)
    {
        return $user->can('Update Academic Year');
    }

    public function delete(User $user, AcademicYear $academicYear)
    {
        return $user->can('Delete Academic Year');
    }
}
