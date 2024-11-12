<?php

namespace App\Http\Controllers\Admin;

use App\Charts\CompaniesUserEngagementChart;
use App\Charts\CompaniesBranchEngagementChart;
use App\Charts\UsersPerBranchChart;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Reports\Admin\EngagementReport;

class CompanyEngagementReportController extends Controller
{
    public function __invoke(Company $school)
    {
        $engagementReport = new EngagementReport(['company_id' => $school->id]);

        $chart = new CompaniesUserEngagementChart($engagementReport);

        $chartT = new CompaniesBranchEngagementChart($engagementReport);

        $chartD = new UsersPerBranchChart($engagementReport);

        return view('admin.schools.report', 
                ['chart' => $chart->build(),'chartT' => $chartT->build(),'chartD' => $chartD->build()], 
                compact('school', 'engagementReport'));
    }
}
