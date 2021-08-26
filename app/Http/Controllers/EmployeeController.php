<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\Warehouse;
use App\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    private $employee;

    public function __construct(Employee $employee)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:User Management');

        $this->authorizeResource(Employee::class, 'employee');

        $this->employee = $employee;
    }

    public function index()
    {
        $employees = $this->employee->getAll()->load(['warehouse']);

        $totalEmployees = $this->employee->countAllEmployees();

        $totalEnabledEmployees = $this->employee->countEnabledEmployees();

        $totalBlockedEmployees = $this->employee->countBlockedEmployees();

        return view('employees.index', compact('employees', 'totalEmployees', 'totalEnabledEmployees', 'totalBlockedEmployees'));
    }

    public function create(Role $role)
    {
        $roles = Role::all()->where('name', '<>', 'System Manager');

        $warehouses = (new Warehouse())->getAllWithoutRelations();

        return view('employees.create', compact('roles', 'warehouses'));
    }

    public function store(StoreEmployeeRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            $user->employee()->create(
                $request->only([
                    'position',
                    'enabled',
                    'company_id',
                    'created_by',
                    'updated_by',
                    'warehouse_id',
                ])
            );

            $user->warehouses()->syncWithPivotValues($request->read, ['type' => 'read']);

            $user->warehouses()->syncWithPivotValues($request->subtract, ['type' => 'subtract']);

            $user->warehouses()->syncWithPivotValues($request->add, ['type' => 'add']);

            $user->assignRole($request->role);
        });

        return redirect()->route('employees.index');
    }

    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    public function edit(Employee $employee, Role $role)
    {
        $employee->load(['user.roles']);

        $roles = Role::all()->where('name', '<>', 'System Manager');

        $warehouses = (new Warehouse())->getAllWithoutRelations();

        return view('employees.edit', compact('employee', 'roles', 'warehouses'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        DB::transaction(function () use ($request, $employee) {
            $employee->user->update($request->only(['name', 'email']));

            $employee->update($request->only(['position', 'enabled', 'updated_by', 'warehouse_id']));

            if ($request->has('role')) {
                $employee->user->syncRoles($request->role);
            }
        });

        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->user->hasRole('System Manager')) {
            return view('errors.permission_denied');
        }

        if ($employee->user->id == auth()->id()) {
            return view('errors.permission_denied');
        }

        $employee->user->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
