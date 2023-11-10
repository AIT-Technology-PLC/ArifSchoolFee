<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;

class UserController extends Controller
{
    public function index(UserDatatable $datatable)
    {
        $totalUsers = User::where('is_admin', 1)->count();

        return $datatable->render('admin.users.index', compact('totalUsers'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        User::create($request->validated() + ['is_admin' => true]);

        return redirect()->route('admin.users.index')->with('successMessage', 'User created successfully');
    }

    public function edit(User $user)
    {
        if (!$user->isAdmin()) {
            return back()->with('failedMessage', 'User not found.');
        }

        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        if (!$user->isAdmin()) {
            return redirect()->route('admin.users.index')->with('failedMessage', 'User not found.');
        }

        $user->update($request->validated());

        return redirect()->route('admin.users.index')->with('successMessage', 'User updated successfully');
    }
}
