<?php

namespace App\Policies;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ContactPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user)
    {
        return $user->can('Read Contact');
    }

    public function view(User $user, Contact $contact)
    {
        return $user->can('Read Contact');
    }

    public function create(User $user)
    {
        return $user->can('Create Contact');
    }

    public function update(User $user, Contact $contact)
    {
        return $user->can('Update Contact');
    }

    public function delete(User $user, Contact $contact)
    {
        return $user->can('Delete Contact');
    }

    public function import(User $user)
    {
        return $user->can('Import Contact');
    }
}
