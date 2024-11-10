<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterReportRequest;
use App\Reports\Admin\EngagementReport;
use App\Reports\Admin\SubscriptionReport;
use App\Charts\CompaniesChart;
use App\Charts\SubscriptionChart;
use App\Charts\SubscriptionByMonthChart;
use App\Models\Company;

class DashboardController extends Controller
{
    public function __invoke(FilterReportRequest $request)
    {
        $engagementReport = new EngagementReport($request->validated());

        $subscriptionReport = new SubscriptionReport();

        $totalSchools = Company::count();

        $chart = new CompaniesChart($engagementReport);

        $chartT = new SubscriptionChart($subscriptionReport);

        $chartD = new SubscriptionByMonthChart($subscriptionReport);

        return view('admin.reports.dashboard',
            ['chart' => $chart->build(), 'chartT' => $chartT->build(), 'chartD' => $chartD->build()], 
            compact('engagementReport', 'subscriptionReport','totalSchools'));
    }
}
