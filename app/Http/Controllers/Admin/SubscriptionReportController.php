<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterReportRequest;
use App\Reports\SubscriptionReport;

class SubscriptionReportController extends Controller
{
    public function __invoke(FilterReportRequest $request)
    {
        $subscriptionReport = new SubscriptionReport($request->validated());

        return view('admin.reports.subscriptions', compact('subscriptionReport'));
    }
}
