<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function edit(Employee $employee, Permission $permission)
    {
        $this->authorize('update', $employee);

        $userRolePermissions = $employee->user->getPermissionsViaRoles()->pluck('name')->toArray();

        $permissions = $permission->whereNotIn('name', $userRolePermissions)->get();

        $userDirectPermissions = $employee->user->getDirectPermissions()->pluck('name');

        return view('permissions.edit', compact('employee', 'permissions', 'userDirectPermissions'));
    }

    public function update(Request $request, Employee $employee)
    {
        $this->authorize('update', $employee);

        $permissionData = $request->validate([
            'permissions' => 'nullable|array',
            'permissions.*' => 'nullable|string',
        ]);

        $permissionData = $permissionData['permissions'] ?? '';

        $employee->user->syncPermissions([$permissionData]);

        return redirect()->back()->with('message', 'Permissions updated successfully');
    }
}
