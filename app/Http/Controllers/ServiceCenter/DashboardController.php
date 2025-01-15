<?php

namespace App\Http\Controllers\ServiceCenter;

use App\Charts\UserWiseFeeCollectionChart;
use App\Http\Controllers\Controller;
use App\Reports\ServiceCenter\DashboardReport;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $dashboardReport = new  DashboardReport();

        $chart = new UserWiseFeeCollectionChart($dashboardReport);

        return view('service-centers.index', ['chart' => $chart->build()], compact('dashboardReport'));
    }
}