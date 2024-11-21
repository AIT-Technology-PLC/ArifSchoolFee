<?php

namespace App\Http\Controllers\Invokable;

use App\Charts\CollectedFeeChart;
use App\Http\Controllers\Controller;
use App\Reports\DashboardReport;

class HomeController extends Controller
{
    public function __invoke()
    {
        if (authUser()->isAdmin()) {
            return redirect()->route('admin.reports.dashboard');
        }
        
        $dashboardReport = new  DashboardReport();

        $chart = new CollectedFeeChart($dashboardReport);

        return view('menu.index', ['chart' => $chart->build()],  compact('dashboardReport'));
    }
}
