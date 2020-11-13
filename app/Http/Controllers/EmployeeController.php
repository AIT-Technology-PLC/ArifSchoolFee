<?php

namespace App\Http\Controllers;

use App\Models\Employee;
use App\Models\Permission;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class EmployeeController extends Controller
{
    private $employee;

    public function __construct(Employee $employee)
    {
        $this->authorizeResource(Employee::class);

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

    public function create()
    {
        return view('employees.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'position' => 'sometimes|required|string',
            'enabled' => 'required|integer|max:1',
        ]);

        DB::transaction(function () use ($data) {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
            ]);

            $permission = Permission::create([
                'settings' => '',
                'warehouses' => 'cr',
                'products' => 'crud',
                'merchandises' => 'crud',
                'manufacturings' => 'crud',
            ]);

            $this->employee->create([
                'user_id' => $user->id,
                'company_id' => auth()->user()->employee->company_id,
                'permission_id' => $permission->id,
                'created_by' => auth()->user()->id,
                'updated_by' => auth()->user()->id,
                'position' => $data['position'],
                'enabled' => $data['enabled'],
            ]);
        });

        return redirect()->route('employees.index');
    }

    public function edit(Employee $employee)
    {
        return view('employees.edit', compact('employee'));
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'position' => 'nullable|string',
            'enabled' => 'required|integer|max:1',
        ]);

        DB::transaction(function () use ($data, $employee) {
            $employee->user->update([
                'name' => $data['name'],
                'email' => $data['email'],
            ]);

            $employee->update([
                'updated_by' => auth()->user()->id,
                'position' => $data['position'],
                'enabled' => $data['enabled'],
            ]);
        });

        return redirect()->route('employees.index');
    }

    public function destroy(Employee $employee)
    {
        //
    }
}
