<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\Employee;

class ToggleEmployeeController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:User Management');

        $this->authorizeResource(Employee::class, 'employee');
    }

    public function __invoke(Employee $employee)
    {
        $employee->toggle();

        return back()->with('successMessage', 'Employee is ' . $employee->isEnabled() ? 'enabled.' : 'disabled.');
    }
}
