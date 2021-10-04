<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Http\Requests\UpdateEmployeeRequest;
use App\Models\Employee;
use App\Models\User;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    private $employee;

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

    public function store(StoreEmployeeRequest $request)
    {
        DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'warehouse_id' => $request->warehouse_id,
            ]);

            $user->employee()->create($request->only(['position', 'enabled']));

            foreach ($request->read ?? [] as $warehouseId) {
                DB::table('user_warehouse')->insert([
                    'user_id' => $user->id,
                    'warehouse_id' => $warehouseId,
                    'type' => 'read',
                ]);
            }

            foreach ($request->add ?? [] as $warehouseId) {
                DB::table('user_warehouse')->insert([
                    'user_id' => $user->id,
                    'warehouse_id' => $warehouseId,
                    'type' => 'add',
                ]);
            }

            foreach ($request->subtract ?? [] as $warehouseId) {
                DB::table('user_warehouse')->insert([
                    'user_id' => $user->id,
                    'warehouse_id' => $warehouseId,
                    'type' => 'subtract',
                ]);
            }

            $user->assignRole($request->role);
        });

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

        $readPermissions = $employee->user->warehouses()->wherePivot('type', 'read')->pluck('warehouse_id');

        $addPermissions = $employee->user->warehouses()->where('type', 'add')->pluck('warehouse_id');

        $subtractPermissions = $employee->user->warehouses()->where('type', 'subtract')->pluck('warehouse_id');

        return view('employees.edit', compact('employee', 'roles', 'warehouses', 'readPermissions', 'addPermissions', 'subtractPermissions'));
    }

    public function update(UpdateEmployeeRequest $request, Employee $employee)
    {
        DB::transaction(function () use ($request, $employee) {
            $employee->user->update($request->only(['name', 'email', 'warehouse_id']));

            $employee->update($request->only(['position', 'enabled']));

            $employee->user->warehouses()->detach();

            foreach ($request->read ?? [] as $warehouseId) {
                DB::table('user_warehouse')->updateOrInsert([
                    'user_id' => $employee->user->id,
                    'warehouse_id' => $warehouseId,
                    'type' => 'read',
                ]);
            }

            foreach ($request->add ?? [] as $warehouseId) {
                DB::table('user_warehouse')->updateOrInsert([
                    'user_id' => $employee->user->id,
                    'warehouse_id' => $warehouseId,
                    'type' => 'add',
                ]);
            }

            foreach ($request->subtract ?? [] as $warehouseId) {
                DB::table('user_warehouse')->updateOrInsert([
                    'user_id' => $employee->user->id,
                    'warehouse_id' => $warehouseId,
                    'type' => 'subtract',
                ]);
            }

            if ($request->has('role')) {
                $employee->user->syncRoles($request->role);
            }
        });

        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {
        if ($employee->user->hasRole('System Manager')) {
            abort(403);
        }

        if ($employee->user->id == auth()->id()) {
            abort(403);
        }

        $employee->user->forceDelete();

        return back()->with('deleted', 'Deleted Successfully');
    }
}
