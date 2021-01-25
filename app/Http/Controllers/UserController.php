<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function destory(User $user)
    {
        $user->forceDelete();

        return redirect()->back('deleted', 'Deleted Successfully');
    }
}
