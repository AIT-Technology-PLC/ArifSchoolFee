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
        $engagementReport = new EngagementReport($company);

        $transactionReport = new TransactionReport(['company_id' => $company->id]);

        $branchesWithUsersCount = $company->warehouses()->withCount(['originalUsers' => fn($q) => $q->whereNot('name', 'onrica support')])->get(['name', 'original_users_count']);

        return view('admin.companies.report', compact('company', 'engagementReport', 'transactionReport', 'branchesWithUsersCount'));
    }
}
