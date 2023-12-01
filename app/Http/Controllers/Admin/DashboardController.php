<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Reports\EngagementReport;
use App\Reports\FeatureReport;

class DashboardController extends Controller
{
    public function __invoke()
    {
        $engagementReport = new EngagementReport;

        $featureReport = new FeatureReport(onlyToday: true);

        $companies = Company::enabled()
            ->withCount([
                'employees' => fn($q) => $q->whereHas('user', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today())),
                'warehouses' => fn($q) => $q->whereHas('originalUsers', fn($q) => $q->whereNot('name', 'onrica support')->whereDate('last_online_at', today())),
            ])->orderBy('employees_count', 'DESC')->orderBy('warehouses_count', 'DESC')->get();

        return view('admin.dashboard', compact('engagementReport', 'featureReport', 'companies'));
    }
}
