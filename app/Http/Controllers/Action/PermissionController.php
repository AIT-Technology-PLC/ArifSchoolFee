<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Employee;
use App\Utilities\PermissionCategorization;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:User Management');
    }

    public function edit(Employee $employee)
    {
        abort_if($employee->user->hasRole('System Manager'), 403);

        $this->authorize('update', $employee);

        $permissions = Permission::whereNot('name', 'LIKE', 'Manage Admin Panel%')->oldest()->pluck('name');

        $permissionCategories = PermissionCategorization::getPermissionCategories();

        $permissionsByCategories = PermissionCategorization::getPermissionsByCategories($permissions);

        $userPermissions = $employee->user->getAllPermissions()->pluck('name');

        $userRolesPermissions = $employee->user->getPermissionsViaRoles()->pluck('name');

        return view('permissions.edit',
            compact(
                'employee',
                'permissionCategories',
                'permissionsByCategories',
                'userPermissions',
                'userRolesPermissions',
            )
        );
    }

    public function update(UpdatePermissionRequest $request, Employee $employee)
    {
        abort_if($employee->user->hasRole('System Manager'), 403);

        $this->authorize('update', $employee);

        $employee->user->syncPermissions($request->permissions);

        return back()->with('message', 'Permissions updated successfully');
    }
}
