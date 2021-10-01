<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdatePermissionRequest;
use App\Models\Employee;
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

        $permissions = $permission->whereNotIn('name', $employee->user->getPermissionsViaRoles()->pluck('name'))
            ->oldest()
            ->pluck('name');

        $permissionsByCategory = $this->categorizePermissions($permissions);

        $userDirectPermissions = $employee->user->getDirectPermissions()->pluck('name');

        return view('permissions.edit', compact('employee', 'permissionsByCategory', 'userDirectPermissions'));
    }

    public function update(UpdatePermissionRequest $request, Employee $employee)
    {
        abort_if($employee->user->hasRole('System Manager'), 403);

        $this->authorize('update', $employee);

        $employee->user->syncPermissions($request->permissions);

        return redirect()->back()->with('message', 'Permissions updated successfully');
    }

    private function categorizePermissions($permissions)
    {
        $permission['gdnPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'gdn'));

        $permission['grnPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'grn'));

        $permission['transferPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'transfer'));

        $permission['damagePermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'damage'));

        $permission['adjustmentPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'adjustment'));

        $permission['sivPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'siv'));

        $permission['merchandisePermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'merchandise'));

        $permission['returnPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'return'));

        $permission['salePermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'sale'));

        $permission['proformaInvoicePermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'proforma invoice'));

        $permission['reservationPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'reservation'));

        $permission['purchasePermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'purchase'));

        $permission['poPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'po'));

        $permission['productPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'product'));

        $permission['warehousePermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'warehouse'));

        $permission['employeePermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'employee'));

        $permission['supplierPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'supplier'));

        $permission['customerPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'customer'));

        $permission['tenderPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'tender'));

        $permission['companyPermissions'] = $permissions->filter(fn($permission) => stristr($permission, 'company'));

        return $permission;
    }
}
