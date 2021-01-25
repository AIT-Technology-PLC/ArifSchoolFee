<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function destroy(User $user)
    {
        $user->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
