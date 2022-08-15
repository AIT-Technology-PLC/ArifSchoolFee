<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ReceivableDatatable;
use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Customer;

class ReceivableController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Receivable Management');

        $this->authorizeResource(Customer::class, 'customer');
    }

    public function index(ReceivableDatatable $datatable)
    {
        $datatable->builder()->setTableId('receivables-datatable')->orderBy(1, 'desc');

        $totalReceivables = money(Credit::unsettled()->get()->sum('creditAmountUnsettled'));

        $totalCustomersWithUnSettlement = Credit::unsettled()->get()->unique('customer_id')->count();

        return $datatable->render('receivables.index', compact('totalReceivables', 'totalCustomersWithUnSettlement'));
    }
}
