<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterReportRequest;
use App\Reports\Admin\EngagementReport;
use App\Charts\CompaniesChart;

class UserReportController extends Controller
{
    public function __invoke(FilterReportRequest $request)
    {
        $engagementReport = new EngagementReport($request->validated());

        $chart = new CompaniesChart($engagementReport);

        return view('admin.reports.users', ['chart' => $chart->build()], compact('engagementReport'));
    }
}
