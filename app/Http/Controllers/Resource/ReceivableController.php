<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ReceivableDatatable;
use App\Http\Controllers\Controller;
use App\Models\Credit;

class ReceivableController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Credit Management');
    }

    public function index(ReceivableDatatable $datatable)
    {
        abort_if(authUser()->cannot('Read Credit'), 403);

        $datatable->builder()->setTableId('receivables-datatable')->orderBy(1, 'desc');

        $totalReceivables = Credit::unsettled()->get()->sum('creditAmountUnsettled');

        $totalCustomers = Credit::unsettled()->get()->unique('customer_id')->count();

        return $datatable->render('receivables.index', compact('totalReceivables', 'totalCustomers'));
    }
}
