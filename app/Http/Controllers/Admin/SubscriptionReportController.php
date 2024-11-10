<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterReportRequest;
use App\Reports\Admin\SubscriptionReport;
use App\Charts\SubscriptionChart;
use App\Charts\SubscriptionByMonthChart;

class SubscriptionReportController extends Controller
{
    public function __invoke(FilterReportRequest $request)
    {
        $subscriptionReport = new SubscriptionReport($request->validated());

        $chart = new SubscriptionChart($subscriptionReport);

        $chartT = new SubscriptionByMonthChart($subscriptionReport);

        return view('admin.reports.subscriptions', 
        ['chart' => $chart->build(), 'chartT' => $chartT->build()],
        compact('subscriptionReport'));
    }
}
