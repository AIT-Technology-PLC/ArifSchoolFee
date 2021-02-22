<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\User;
use Illuminate\Http\Request;
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'position' => 'required|string',
            'enabled' => 'required|integer|max:1',
            'role' => 'required|string',
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $this->employee->create([
                'user_id' => $user->id,
                'company_id' => auth()->user()->employee->company_id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'position' => $data['position'],
                'enabled' => $data['enabled'],
            ]);

            $user->assignRole($data['role']);
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
                'updated_by' => auth()->user()->id,
                'position' => $data['position'],
                'enabled' => $data['enabled'] ?? $employee->enabled,
            ]);

            $employee->user->syncRoles([$data['role']]);
        });

        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {
        //
    }
}
