<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\Staff;
use App\Models\Student;
use App\Models\Warehouse;

class HomeController extends Controller
{
    public function __invoke()
    {
        if (authUser()->isAdmin()) {
            return redirect()->route('admin.reports.dashboard');
        }
        
        $totalStudent = Student::count();

        $totalStaff = Staff::count();

        $thisMonthRevenue = Staff::count();

        $activeBranches = Warehouse::active()->count();

        return view('menu.index', compact('totalStudent', 'totalStaff', 'thisMonthRevenue', 'activeBranches'));
    }
}
