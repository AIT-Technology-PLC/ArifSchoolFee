<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterRequest;

class SalesReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sales Report');
        turnOffPreparedStatementEmulation();
        turnOffMysqlStictMode();
    }

    public function __invoke(FilterRequest $request)
    {
        abort_if(authUser()->cannot('Read Sales Return Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        return view('reports.sales-return', compact('warehouses'));
    }
}