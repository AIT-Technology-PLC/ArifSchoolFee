<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreRoleRequest;
use App\Http\Requests\Admin\UpdateRoleRequest;
use App\Utilities\PermissionCategorization;
use Spatie\Permission\Models\Permission;
use App\DataTables\Admin\RoleDatatable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    public function index(RoleDatatable $datatable)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $datatable->builder()->setTableId('roles-datatable')->orderBy(1, 'asc');

        $totalRoles = Role::Count();

        return $datatable->render('admin.roles.index', compact('totalRoles'));
    }

    public function create()
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $permissions = Permission::whereNot('name', 'LIKE', 'Manage Admin Panel%')->oldest()->pluck('name');

        $permissionCategories = PermissionCategorization::getPermissionCategories();

        $permissionsByCategories = PermissionCategorization::getPermissionsByCategories($permissions);

        return view('admin.roles.create', compact('permissionCategories', 'permissionsByCategories'));
    }

    public function store(StoreRoleRequest $request)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        DB::transaction(function () use ($request) {
            $role = Role::create($request->safe()->except('permissions'));

            $role->syncPermissions($request->permissions);
        });

        return redirect()->route('admin.roles.index')->with('successMessage', 'New Role Created!');
    }

    public function edit(Role $role)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        $permissions = Permission::whereNot('name', 'LIKE', 'Manage Admin Panel%')->oldest()->pluck('name');

        $permissionCategories = PermissionCategorization::getPermissionCategories();

        $permissionsByCategories = PermissionCategorization::getPermissionsByCategories($permissions);

        $rolePermissions = $role->getAllPermissions()->pluck('name');

        return view('admin.roles.edit', compact('role', 'permissionCategories', 'permissionsByCategories', 'rolePermissions'));
    }

    public function update(UpdateRoleRequest $request, Role $role)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        DB::transaction(function () use ($request, $role) {
            $role->update($request->safe()->except('permissions'));

            $role->syncPermissions($request->permissions);
        });

        return redirect()->route('admin.roles.index')->with('successMessage', 'Updated Successfully.');
    }
}
