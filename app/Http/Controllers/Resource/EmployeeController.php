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

        $this->authorizeResource(Employee::class, 'employee');
    }

    public function index(EmployeeDatatable $datatable)
    {
        $datatable->builder()->setTableId('employees-datatable')->orderBy(0, 'asc');

        $totalEmployees = Employee::count();

        $totalEnabledEmployees = Employee::enabled()->count();

        $totalBlockedEmployees = Employee::disabled()->count();

        $warehouses = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        return $datatable->render('employees.index', compact('totalEmployees', 'totalEnabledEmployees', 'totalBlockedEmployees', 'warehouses'));
    }

    public function create()
    {
        $roles = Role::all()->where('name', '<>', 'System Manager');

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        return view('employees.create', compact('roles', 'warehouses'));
    }

    public function store(StoreEmployeeRequest $request, CreateUserAction $action)
    {
        if (limitReached('user', Employee::enabled()->count())) {
            return back()->with('limitReachedMessage', 'You have reached the allowed number of users as per your package.');
        }

        $action->execute($request->validated());

        return redirect()->route('employees.index');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee)
    {
        $employee->load(['user.roles', 'user.warehouse', 'user.warehouses']);

        $roles = Role::all()->where('name', '<>', 'System Manager');

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $warehousePermissions = $employee->user->warehouses->groupBy('pivot.type');

        return view('employees.edit', compact('employee', 'roles', 'warehouses', 'warehousePermissions'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee, UpdateUserAction $action)
    {
        $action->execute($employee, $request->validated());

        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {
        $employee->user->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
