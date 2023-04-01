<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;
use App\Reports\CreditReport;

class CreditReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Credit Report');
    }

    public function index(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Credit Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $creditReport = new CreditReport($request->validated());

        return view('reports.credit', compact('creditReport', 'warehouses'));
    }
}