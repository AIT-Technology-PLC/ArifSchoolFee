<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;

class LoginPermissionController extends Controller
{
    public function index()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $users = User::where(function ($query) {$query->where('is_admin', 1)->orWhereIn('user_type', ['call_center', 'bank']);})->get();

        $totalEnabledUsers = User::where('is_admin', 1)->allowed()->count();

        $totalBlockedUsers = User::where('is_admin', 1)->notAllowed()->count();

        return view('admin.login-permissions.index', compact('users', 'totalEnabledUsers', 'totalBlockedUsers'));
    }
}
