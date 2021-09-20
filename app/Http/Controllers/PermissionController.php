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
        if ($employee->user->hasRole('System Manager')) {
            abort(403);
        }

        if ($employee->user->id == auth()->id()) {
            abort(403);
        }

        $this->authorize('update', $employee);

        $userRolePermissions = $employee->user->getPermissionsViaRoles()->pluck('name')->toArray();

        $permissions = $permission->whereNotIn('name', $userRolePermissions)->oldest()->get();

        $permissionsByCategory = $this->permissionsByCategory($permissions);

        $userDirectPermissions = $employee->user->getDirectPermissions()->pluck('name');

        return view('permissions.edit', compact('employee', 'permissionsByCategory', 'userDirectPermissions'));
    }

    public function update(UpdatePermissionRequest $request, Employee $employee)
    {
        if ($employee->user->hasRole('System Manager')) {
            abort(403);
        }

        if ($employee->user->id == auth()->id()) {
            abort(403);
        }

        $this->authorize('update', $employee);

        $employee->user->syncPermissions($request->permissions);

        return redirect()->back()->with('message', 'Permissions updated successfully');
    }

    public function permissionsByCategory($permissions)
    {
        $permission['gdnPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'gdn'))->pluck('name')->toArray();

        $permission['grnPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'grn'))->pluck('name')->toArray();

        $permission['transferPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'transfer'))->pluck('name')->toArray();

        $permission['damagePermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'damage'))->pluck('name')->toArray();

        $permission['adjustmentPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'adjustment'))->pluck('name')->toArray();

        $permission['sivPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'siv'))->pluck('name')->toArray();

        $permission['merchandisePermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'merchandise'))->pluck('name')->toArray();

        $permission['returnPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'return'))->pluck('name')->toArray();

        $permission['salePermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'sale'))->pluck('name')->toArray();

        $permission['proformaInvoicePermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'proforma invoice'))->pluck('name')->toArray();

        $permission['reservationPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'reservation'))->pluck('name')->toArray();

        $permission['purchasePermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'purchase'))->pluck('name')->toArray();

        $permission['poPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'po'))->pluck('name')->toArray();

        $permission['productPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'product'))->pluck('name')->toArray();

        $permission['warehousePermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'warehouse'))->pluck('name')->toArray();

        $permission['employeePermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'employee'))->pluck('name')->toArray();

        $permission['supplierPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'supplier'))->pluck('name')->toArray();

        $permission['customerPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'customer'))->pluck('name')->toArray();

        $permission['tenderPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'tender'))->pluck('name')->toArray();

        $permission['pricePermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'price'))->pluck('name')->toArray();

        $permission['companyPermissions'] = $permissions->filter(fn($permission) => stristr($permission->name, 'company'))->pluck('name')->toArray();

        return $permission;
    }
}
