<?php

namespace App\Http\Controllers\Resource;

use App\Actions\CreateUserAction;
use App\Actions\UpdateUserAction;
use App\DataTables\EmployeeDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Compensation;
use App\Models\Department;
use App\Models\Employee;
use App\Models\Warehouse;
use App\Scopes\ActiveWarehouseScope;
use App\Utilities\PayrollGenerator;
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
        if (limitReached('user', Employee::enabled()->count())) {
            return back()->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'users']));
        }

        $roles = Role::all()->where('name', '<>', 'System Manager');

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $departments = Department::orderBy('name')->get(['id', 'name']);

        $compensations = Compensation::orderBy('name')->active()->canBeInputtedManually()->get(['id', 'name']);

        return view('employees.create', compact('roles', 'warehouses', 'departments', 'compensations'));
    }

    public function store(StoreEmployeeRequest $request, CreateUserAction $action)
    {
        if (limitReached('user', Employee::enabled()->count())) {
            return back()->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'users']));
        }

        $action->execute($request->validated());

        return redirect()->route('employees.index');
    }

    public function show(Employee $employee)
    {
        $employee->load(['user.roles', 'user.warehouse', 'department', 'warnings', 'expenseClaims', 'employeeCompensations']);

        $payroll = PayrollGenerator::calculate($employee);

        return view('employees.show', compact('employee', 'payroll'));
    }

    public function edit(Employee $employee)
    {
        $employee->load(['user.roles', 'user.warehouse', 'user.warehouses']);

        $roles = Role::all()->where('name', '<>', 'System Manager');

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $departments = Department::orderBy('name')->get(['id', 'name']);

        $compensations = Compensation::orderBy('name')->active()->canBeInputtedManually()->get(['id', 'name']);

        $warehousePermissions = $employee->user->warehouses->groupBy('pivot.type');

        return view('employees.edit', compact('employee', 'roles', 'warehouses', 'warehousePermissions', 'departments', 'compensations'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee, UpdateUserAction $action)
    {
        if (!$employee->isEnabled() && $request->validated('enabled') && limitReached('user', Employee::enabled()->count())) {
            $action->execute($employee, $request->safe()->except('enabled'));

            return redirect()->route('employees.index')->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'users']));
        }

        $action->execute($employee, $request->validated());

        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {
        $employee->user->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
