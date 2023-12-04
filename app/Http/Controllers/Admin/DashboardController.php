<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
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

        $companies = Company::enabled()
            ->withCount([
                'employees' => fn($q) => $q->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today())),
                'warehouses' => fn($q) => $q->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today())),
            ])->orderBy('employees_count', 'DESC')->orderBy('warehouses_count', 'DESC')->get();

        return view('admin.reports.dashboard', compact('engagementReport', 'transactionReport', 'subscriptionReport', 'companies'));
    }
}
