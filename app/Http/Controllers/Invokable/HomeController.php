<?php

namespace App\Http\Controllers\Invokable;

use App\Http\Controllers\Controller;
use App\Models\Staff;

class HomeController extends Controller
{
    public function __invoke()
    {
        if (authUser()->isAdmin()) {
            return redirect()->route('admin.reports.dashboard');
        }
        
        $totalStudent = Staff::count();

        $totalStaff = Staff::count();

        $thisMonthRevenue = Staff::count();

        return view('menu.index', compact('totalStudent', 'totalStaff', 'thisMonthRevenue'));
    }
}
