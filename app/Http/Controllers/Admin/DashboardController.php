<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Reports\EngagementReport;
use App\Reports\SubscriptionReport;
use App\Reports\TransactionReport;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $engagementReport = new EngagementReport;

        $transactionReport = new TransactionReport(['transaction_period' => [today(), today()]]);

        $subscriptionReport = new SubscriptionReport;

        return view('admin.reports.dashboard', compact('engagementReport', 'transactionReport', 'subscriptionReport'));
    }
}
