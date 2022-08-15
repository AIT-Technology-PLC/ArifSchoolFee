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

    public function index(ReceivableDatatable $datatable, Customer $customer)
    {
        $datatable->builder()->setTableId('receivables-datatable')->orderBy(1, 'desc');

        $totalReceivables = Credit::unSettled()->count();

        $totalCustomersWithUnSettlement = Credit::customerWithUnSettlement()->count();

        return $datatable->render('receivables.index', compact('totalReceivables', 'totalCustomersWithUnSettlement'));
    }
}
