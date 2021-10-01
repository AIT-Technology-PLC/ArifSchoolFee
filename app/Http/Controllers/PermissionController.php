<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Employee;
use App\Models\User;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:User Management');
    }

    public function edit(Employee $employee, Permission $permission)
    {
        abort_if($employee->user->hasRole('System Manager'), 403);

        $this->authorize('update', $employee);

        $permissionCategories = User::PERMISSION_CATEGORIES;

        $permissions = $permission->whereNotIn('name', $employee->user->getPermissionsViaRoles()->pluck('name'))
            ->oldest()
            ->pluck('name');

        $permissionsByCategory = $this->categorizePermissions($permissionCategories, $permissions);

        $userDirectPermissions = $employee->user->getDirectPermissions()->pluck('name');

        return view('permissions.edit', compact('employee', 'permissionCategories', 'permissionsByCategory', 'userDirectPermissions'));
    }

    public function update(UpdatePermissionRequest $request, Employee $employee)
    {
        abort_if($employee->user->hasRole('System Manager'), 403);

        $this->authorize('update', $employee);

        $employee->user->syncPermissions($request->permissions);

        return redirect()->back()->with('message', 'Permissions updated successfully');
    }

    private function categorizePermissions($permissionCategories, $permissions)
    {
        $permissionsByCategory = [];

        foreach ($permissionCategories as $key => $value) {

            $permissionsByCategory[$key] = $permissions
                ->filter(function ($permission) use ($key) {
                    return stristr($permission, $key);
                })
                ->toArray();

        }

        return $permissionsByCategory;
    }
}
