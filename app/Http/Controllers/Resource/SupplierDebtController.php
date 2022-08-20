<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\DebtDatatable;
use App\Http\Controllers\Controller;
use App\Models\Debt;
use App\Models\Supplier;

class SupplierDebtController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Supplier Management');

        $this->middleware('isFeatureAccessible:Debt Management');
    }

    public function index(Supplier $supplier, DebtDatatable $datatable)
    {
        $this->authorize('view', $supplier);

        $this->authorize('viewAny', Debt::class);

        $datatable->builder()->setTableId('suppliers-debts-datatable')->orderBy(1, 'desc');

        $totalDebts = $supplier->debts()->count();

        $totalSettled = $supplier->debts()->settled()->count();

        $totalPartiallySettled = $supplier->debts()->partiallySettled()->count();

        $totalNotSettledAtAll = $supplier->debts()->noSettlements()->count();

        $totalDebtAmountProvided = $supplier->debts()->sum('debt_amount');

        $currentDebtBalance = $totalDebtAmountProvided - $supplier->debts()->sum('debt_amount_settled');

        $averageDebtSettlementDays = $supplier->debts()->settled()->averageDebtSettlementDays();

        $currentDebtLimit = $supplier->debt_amount_limit > 0 ? ($supplier->debt_amount_limit - $currentDebtBalance) : $supplier->debt_amount_limit;

        return $datatable->render('suppliers.debts.index', compact(
            'supplier',
            'totalDebts',
            'totalSettled',
            'totalPartiallySettled',
            'totalNotSettledAtAll',
            'totalDebtAmountProvided',
            'currentDebtBalance',
            'averageDebtSettlementDays',
            'currentDebtLimit')
        );
    }
}
