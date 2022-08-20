<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\PayableDatatable;
use App\Http\Controllers\Controller;
use App\Models\Debt;

class PayableController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Debt Management');
    }

    public function index(PayableDatatable $datatable)
    {
        abort_if(authUser()->cannot('Read Debt'), 403);

        $datatable->builder()->setTableId('payables-datatable')->orderBy(1, 'desc');

        $totalPayables = Debt::unsettled()->get()->sum('debtAmountUnsettled');

        $totalSuppliers = Debt::unsettled()->get()->unique('supplier_id')->count();

        return $datatable->render('payables.index', compact('totalPayables', 'totalSuppliers'));
    }
}
