<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Reports\EngagementReport;
use App\Reports\TransactionReport;

class CompanyEngagementReportController extends Controller
{
    public function __invoke(Company $company)
    {
        $engagementReport = new EngagementReport(['company_id' => $company->id]);

        $transactionReport = new TransactionReport(['company_id' => $company->id]);

        return view('admin.companies.report', compact('company', 'engagementReport', 'transactionReport'));
    }
}
