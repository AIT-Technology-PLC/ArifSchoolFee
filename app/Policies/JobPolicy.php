<?php

namespace App\Policies;

use App\Models\Job;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class JobPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Job');
    }

    public function view(User $user, Job $job)
    {
        return $user->can('Read Job');
    }

    public function create(User $user)
    {
        return $user->can('Create Job');
    }

    public function update(User $user, Job $job)
    {
        $permission = 'Update Job';

        if ($job->isApproved() && !$job->isStarted()) {
            $permission = 'Update Approved Job';
        }

        return $user->can($permission);
    }

    public function approve(User $user, Job $job)
    {
        return $user->can('Approve Job');
    }

    public function delete(User $user, Job $job)
    {
        return $user->can('Delete Job');
    }

    public function close(User $user, Job $job)
    {
        return $user->can('Close Job');
    }

    public function addExtra(User $user, Job $job)
    {
        return $user->can('Add Extra Job');
    }

    public function subtractExtra(User $user, Job $job)
    {
        return $user->can('Subtract Extra Job');
    }

    public function plan(User $user)
    {
        return $user->can('Plan Job');
    }

    public function addToWip(User $user)
    {
        return $user->can('Update Wip Job');
    }

    public function addToAvailable(User $user)
    {
        return $user->can('Update Available Job');
    }
}
