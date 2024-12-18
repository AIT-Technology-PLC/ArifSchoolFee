<?php

namespace App\Http\Controllers\CallCenter;

use App\Charts\UserWiseFeeCollectionChart;
use App\Http\Controllers\Controller;
use App\Reports\CallCenter\DashboardReport;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $dashboardReport = new  DashboardReport();

        $chart = new UserWiseFeeCollectionChart($dashboardReport);

        return view('call-centers.index', ['chart' => $chart->build()], compact('dashboardReport'));
    }
}