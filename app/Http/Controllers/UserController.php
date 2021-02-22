<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function destroy(User $user)
    {
        if ($user->hasRole('System Manager')) {
            return redirect('/permission-denied');
        }

        $user->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
