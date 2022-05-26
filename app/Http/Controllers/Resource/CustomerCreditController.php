<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CreditDatatable;
use App\Http\Controllers\Controller;
use App\Models\Credit;
use App\Models\Customer;

class CustomerCreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Customer Management');

        $this->middleware('isFeatureAccessible:Credit Management');
    }

    public function index(Customer $customer, CreditDatatable $datatable)
    {
        $this->authorize('view', $customer);

        $this->authorize('viewAny', Credit::class);

        $datatable->builder()->setTableId('customers-credits-datatable')->orderBy(1, 'desc');

        $totalCredits = $customer->credits()->count();

        $totalSettled = $customer->credits()->settled()->count();

        $totalPartiallySettled = $customer->credits()->partiallySettled()->count();

        $totalNotSettledAtAll = $customer->credits()->noSettlements()->count();

        $totalCreditAmountProvided = $customer->credits()->sum('credit_amount');

        $currentCreditBalance = $totalCreditAmountProvided - $customer->credits()->sum('credit_amount_settled');

        $averageCreditSettlementDays = $customer->credits()->settled()->averageCreditSettlementDays();

        $currentCreditLimit = $customer->credit_amount_limit > 0 ? ($customer->credit_amount_limit - $currentCreditBalance) : $customer->credit_amount_limit;

        return $datatable->render('customers.credits.index', compact(
            'customer',
            'totalCredits',
            'totalSettled',
            'totalPartiallySettled',
            'totalNotSettledAtAll',
            'totalCreditAmountProvided',
            'currentCreditBalance',
            'averageCreditSettlementDays',
            'currentCreditLimit')
        );
    }
}
