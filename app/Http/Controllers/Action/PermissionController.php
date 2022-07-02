<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Employee;
use App\Models\Pad;
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

        $permissions = Permission::whereNotIn('name', $employee->user->getPermissionsViaRoles()->pluck('name'))
            ->oldest()
            ->pluck('name');

        $permissionCategories = PermissionCategorization::getPermissionCategories();

        $permissionsByCategories = PermissionCategorization::getPermissionsByCategories($permissions);

        $userDirectPermissions = $employee->user->getDirectPermissions()->pluck('name');

        $pads = Pad::with('padPermissions.pad')->enabled()->get();

        $userPadPermissions = $employee->user->padPermissions()->pluck('pad_permission_id');

        return view('permissions.edit',
            compact(
                'employee',
                'permissionCategories',
                'permissionsByCategories',
                'userDirectPermissions',
                'pads',
                'userPadPermissions'
            )
        );
    }

    public function update(UpdatePermissionRequest $request, Employee $employee)
    {
        abort_if($employee->user->hasRole('System Manager'), 403);

        $this->authorize('update', $employee);

        $employee->user->syncPermissions($request->permissions);

        $employee->user->padPermissions()->sync($request->validated('padPermissions') ?? []);

        return back()->with('message', 'Permissions updated successfully');
    }
}
