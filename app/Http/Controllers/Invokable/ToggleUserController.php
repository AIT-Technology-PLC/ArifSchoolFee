<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\User;

class ToggleUserController extends Controller
{
    public function __invoke(User $user)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $user->toggle();

        return back()->with('successMessage', 'Admin is ' . $user->isAllowed() ? 'enabled.' : 'disabled.');
    }
}
