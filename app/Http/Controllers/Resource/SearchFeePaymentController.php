<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\DataTables\SearchFeePaymentDatatable;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Warehouse;

class SearchFeePaymentController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Collect Fee');
    }

    public function index(SearchFeePaymentDatatable $datatable)
    {
        abort_if(authUser()->cannot('Search Fee Payment'), 403);

        $datatable->builder()->setTableId('search-fee-payments-datatable')->orderBy(0, 'asc');

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);

        $sections = Section::orderBy('name')->get(['id', 'name']);

        return $datatable->render('search-fee-payments.index', compact('branches', 'classes', 'sections'));
    }
}
