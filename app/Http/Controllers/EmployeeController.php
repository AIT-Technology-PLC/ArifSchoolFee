<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreEmployeeRequest;
use App\Models\Employee;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class EmployeeController extends Controller
{
    private $employee;

    public function __construct(Employee $employee)
    {
        $this->authorizeResource(Employee::class, 'employee');

        $this->employee = $employee;
    }

    public function index()
    {
        $employees = $this->employee->getAll();

        $totalEmployees = $this->employee->countAllEmployees();

        $totalEnabledEmployees = $this->employee->countEnabledEmployees();

        $totalBlockedEmployees = $this->employee->countBlockedEmployees();

        return view('employees.index', compact('employees', 'totalEmployees', 'totalEnabledEmployees', 'totalBlockedEmployees'));
    }

    public function create(Role $role)
    {
        $roles = Role::all()->where('name', '<>', 'System Manager');

        return view('employees.create', compact('roles'));
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
                ])
            );

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

        return view('employees.edit', compact('employee', 'roles'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'position' => 'required|string',
            'enabled' => 'sometimes|required|integer|max:1',
            'role' => 'sometimes|required|string',
        ]);

        DB::transaction(function () use ($data, $employee) {
            $employee->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            $employee->update([
                'updated_by' => auth()->id(),
                'position' => $data['position'],
                'enabled' => $data['enabled'] ?? $employee->enabled,
            ]);

            if (Arr::has($data, 'role')) {
                $employee->user->syncRoles([$data['role']]);
            };
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
