<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterReportRequest;
use App\Reports\Admin\EngagementReport;
use App\Reports\Admin\SubscriptionReport;
use App\Reports\Admin\TransactionReport;

class DashboardController extends Controller
{
    public function __invoke(FilterReportRequest $request)
    {
        $engagementReport = new EngagementReport($request->validated());

        $transactionReport = new TransactionReport(['transaction_period' => [today(), today()]]);

        $subscriptionReport = new SubscriptionReport;

        return view('admin.reports.dashboard', compact('engagementReport', 'transactionReport', 'subscriptionReport'));
    }
}
