<?php

namespace App\Http\Controllers;

use App\Actions\CreateUserAction;
use App\Actions\UpdateUserAction;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\Warehouse;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:User Management');

        $this->authorizeResource(Employee::class, 'employee');
    }

    public function index()
    {
        $employees = Employee::with(['user.roles', 'user.warehouse', 'createdBy', 'updatedBy'])->get();

        $totalEmployees = Employee::count();

        $totalEnabledEmployees = Employee::enabled()->count();

        $totalBlockedEmployees = Employee::disabled()->count();

        return view('employees.index', compact('employees', 'totalEmployees', 'totalEnabledEmployees', 'totalBlockedEmployees'));
    }

    public function create()
    {
        $roles = Role::all()->where('name', '<>', 'System Manager');

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        return view('employees.create', compact('roles', 'warehouses'));
    }

    public function store(StoreEmployeeRequest $request, CreateUserAction $action)
    {
        $action->execute($request);

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
        $action->execute($employee, $request);

        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {
        $employee->user->forceDelete();

        return back()->with('deleted', 'Deleted Successfully');
    }
}
