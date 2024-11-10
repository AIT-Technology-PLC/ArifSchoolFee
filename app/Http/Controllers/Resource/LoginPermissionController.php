<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\Employee;

class LoginPermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:User Management');

        $this->authorizeResource(Employee::class, 'employee');
    }

    public function index()
    {
        $employees = Employee::all();

        $totalEnabledEmployees = Employee::enabled()->count();

        $totalBlockedEmployees = Employee::disabled()->count();

        return view('login-permissions.index', compact('employees', 'totalEnabledEmployees', 'totalBlockedEmployees'));
    }
}
