<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\FilterReportRequest;
use App\Models\Company;
use App\Reports\FeatureReport;

class TransactionReportController extends Controller
{
    public function __invoke(FilterReportRequest $request)
    {
        $companies = Company::all();

        $featureReport = new FeatureReport($request->validated());

        return view('admin.reports.transactions', compact('companies', 'featureReport'));
    }
}
