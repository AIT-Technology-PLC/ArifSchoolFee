<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ReceivableDatatable;
use App\Http\Controllers\Controller;
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
        $datatable->builder()->setTableId('receivables-datatable')->orderBy(1, 'desc'));

        $totalUnSettledCredits = Customer::count();

        $totalCustomersWithUnSettlement = Customer::count();

        return $datatable->render('jobs.index', compact('totalUnSettledCredits', 'totalCustomersWithUnSettlment'));
    }
}
