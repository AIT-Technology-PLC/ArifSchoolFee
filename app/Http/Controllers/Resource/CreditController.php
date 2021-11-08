<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CreditDatatable;
use App\Http\Controllers\Controller;
use App\Models\Credit;

class CreditController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Credit Management');

        $this->authorizeResource(Credit::class, 'credit');
    }

    public function index(CreditDatatable $datatable)
    {
        $datatable->builder()->orderBy(7, 'desc');

        $totalCredits = Credit::count();

        $totalSettled = Credit::settled()->count();

        $totalPartiallySettled = Credit::partiallySettled()->count();

        $totalNotSettledAtAll = Credit::noSettlements()->count();

        return $datatable->render('credits.index', compact('totalCredits', 'totalSettled', 'totalPartiallySettled', 'totalNotSettledAtAll'));
    }

    public function show(Credit $credit)
    {
        $credit->load(['gdn', 'customer', 'creditSettlements']);

        return view('credits.show', compact('credit'));
    }

    public function destroy(Credit $credit)
    {
        if ($credit->settlement_percentage > 0) {
            return back()->with('failedMessage', 'Deleting a credit that has settlements is not allowed.');
        }

        $credit->forceDelete();

        return back()->with('deleted', 'Deleted successfully');
    }
}
