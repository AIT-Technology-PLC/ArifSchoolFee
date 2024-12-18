<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\UserDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class UserController extends Controller
{
    public function index(UserDatatable $datatable)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $totalAdmins = User::where('is_admin', 1)->count();

        $totalCallCenterUsers = User::where('user_type', 'call_center')->count();

        $totalBankUsers = User::where('user_type', 'bank')->count();

        return $datatable->render('admin.users.index', compact('totalAdmins', 'totalCallCenterUsers', 'totalBankUsers'));
    }

    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return view('admin.users.create');
    }

    public function store(StoreUserRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        DB::transaction(function () use ($request) {
            if ($request->user_type != 'bank') {
                $request->merge(['bank_name' => null]);
            }

            if ($request->user_type == 'admin') {
                $user = User::create($request->validated() + ['is_admin' => true]);

                $user->syncPermissions(
                    Permission::where('name', 'LIKE', 'Manage Admin Panel%')->get()
                );
            }else {
                $user = User::create($request->validated());

                $user->syncPermissions(
                    Permission::where('name','LIKE', 'Manage Schools Payment')->get()
                );
            }

        });

        return redirect()->route('admin.users.index')->with('successMessage', 'User created successfully');
    }

    public function edit(User $user)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        if (!$user->isAdmin() &&  !$user->isCallCenter() && !$user->isBank()) {
            return back()->with('failedMessage', 'User not found.');
        }

        $user->load(['permissions']);

        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        if (!$user->isAdmin() &&  !$user->isCallCenter() && !$user->isBank()) {
            return redirect()->route('admin.users.index')->with('failedMessage', 'User not found.');
        }

        DB::transaction(function () use ($request, $user) {
            if ($request->user_type != 'bank') {
                $request->merge(['bank_name' => null]);
            }
            
            if ($request->user_type == 'admin') {
                $user->update($request->validated()+ ['is_admin' => true]);
            }else {
                $user->update($request->validated()+ ['is_admin' => false]);
            }
        });

        $user->update($request->validated());

        return redirect()->route('admin.users.index')->with('successMessage', 'User updated successfully');
    }
}
