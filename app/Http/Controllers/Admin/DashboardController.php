<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterReportRequest;
use App\Reports\Admin\EngagementReport;
use App\Reports\Admin\SubscriptionReport;
use App\Reports\Admin\TransactionReport;
use App\Charts\CompaniesChart;
use App\Charts\SubscriptionChart;

class DashboardController extends Controller
{
    public function __invoke(FilterReportRequest $request)
    {
        $engagementReport = new EngagementReport($request->validated());

        $subscriptionReport = new SubscriptionReport();

        $chart = new CompaniesChart($engagementReport);

        $chartT = new SubscriptionChart($subscriptionReport);

        return view('admin.reports.dashboard',
            ['chart' => $chart->build(), 'chartT' => $chartT->build()], 
            compact('engagementReport', 'subscriptionReport'));
    }
}
