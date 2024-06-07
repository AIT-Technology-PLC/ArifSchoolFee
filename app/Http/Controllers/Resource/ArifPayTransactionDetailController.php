<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\ArifPayTransactionDetail;
use App\DataTables\ArifPayTransactionDatatable;

class ArifPayTransactionDetailController extends Controller
{
    public function index(ArifPayTransactionDatatable $datatable)
    {
        $datatable->builder()->setTableId('arifpay-transaction-datatable')->orderBy(1, 'asc');

        $totalTransactions = ArifPayTransactionDetail::count();
      
        return $datatable->render('arifpay-transactions.index', compact('totalTransactions'));
    }
}
