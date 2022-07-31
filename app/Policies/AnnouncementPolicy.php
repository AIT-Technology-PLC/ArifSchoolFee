<?php

namespace App\Policies;

use App\Models\Announcement;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AnnouncementPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return true;
    }

    public function view(User $user, Announcement $announcement)
    {
        return $user->can('Read Announcement');
    }

    public function create(User $user)
    {
        return $user->can('Create Announcement');
    }

    public function update(User $user)
    {
        return $user->can('Update Announcement');
    }

    public function delete(User $user, Announcement $announcement)
    {
        return $user->can('Delete Announcement');
    }

    public function approve(User $user, Announcement $announcement)
    {
        return $user->can('Approve Announcement');
    }
}
