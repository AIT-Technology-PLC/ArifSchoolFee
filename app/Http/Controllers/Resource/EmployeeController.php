<?php

namespace App\Http\Controllers\Resource;

use App\Actions\CreateUserAction;
use App\Actions\UpdateUserAction;
use App\DataTables\EmployeeDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\Warehouse;
use App\Scopes\ActiveWarehouseScope;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:User Management');

        $this->authorizeResource(Employee::class, 'user');
    }

    public function index(EmployeeDatatable $datatable)
    {
        $datatable->builder()->setTableId('employees-datatable')->orderBy(0, 'asc');

        $totalEmployees = Employee::count();

        $totalEnabledEmployees = Employee::enabled()->count();

        $totalBlockedEmployees = Employee::disabled()->count();

        $warehouses = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        return $datatable->render('users.index', compact('totalEmployees', 'totalEnabledEmployees', 'totalBlockedEmployees', 'warehouses'));
    }

    public function create()
    {
        $roles = Role::all()->where('name', '<>', 'System Manager');

        $branches = Warehouse::orderBy('name')->get(['id', 'name']);

        return view('users.create', compact('roles', 'branches'));
    }

    public function store(StoreEmployeeRequest $request, CreateUserAction $action)
    {
        $action->execute($request->validated());

        return redirect()->route('users.index')->with('successMessage', 'New User Created Successfully.');
    }

    public function show(Employee $user)
    {
        $user->load(['user.roles', 'user.warehouse']);

        return view('users.show', compact('user'));
    }

    public function edit(Employee $user)
    {
        $user->load(['user.roles', 'user.warehouse', 'user.warehouses']);

        $roles = Role::all()->where('name', '<>', 'System Manager');

        $branches = Warehouse::orderBy('name')->get(['id', 'name']);

        $branchPermissions = $user->user->warehouses->groupBy('pivot.type');

        return view('users.edit', compact('user', 'roles', 'branches', 'branchPermissions'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $user, UpdateUserAction $action)
    {
        $action->execute($user, $request->validated());

        return redirect()->route('users.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(Employee $user)
    {
        $user->user->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
