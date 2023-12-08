<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterReportRequest;
use App\Reports\EngagementReport;

class UserReportController extends Controller
{
    public function __invoke(FilterReportRequest $request)
    {
        $engagementReport = new EngagementReport($request->validated());

        return view('admin.reports.users', compact('engagementReport'));
    }
}
