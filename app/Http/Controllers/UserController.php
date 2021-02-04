<?php

namespace App\Http\Controllers;

use App\User;

class UserController extends Controller
{
    public function destroy(User $user)
    {
        if ($user->employee->permission_id == 1) {
            return redirect('/permission-denied');
        }

        $user->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
