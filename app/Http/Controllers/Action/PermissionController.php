<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Employee;
use App\Services\PermissionCategorizationService;
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

        $permissions = Permission::whereNotIn('name', $employee->user->getPermissionsViaRoles()->pluck('name'))
            ->oldest()
            ->pluck('name');

        $permissionCategories = PermissionCategorizationService::getPermissionCategories();

        $permissionsByCategories = PermissionCategorizationService::getPermissionsByCategories($permissions);

        $userDirectPermissions = $employee->user->getDirectPermissions()->pluck('name');

        return view('permissions.edit', compact('employee', 'permissionCategories', 'permissionsByCategories', 'userDirectPermissions'));
    }

    public function update(UpdatePermissionRequest $request, Employee $employee)
    {
        abort_if($employee->user->hasRole('System Manager'), 403);

        $this->authorize('update', $employee);

        $employee->user->syncPermissions($request->permissions);

        return redirect()->back()->with('message', 'Permissions updated successfully');
    }
}
