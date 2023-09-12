<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterProfitReportRequest;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Reports\ProfitReport;

class ProfitReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Profit Report');
        turnOffPreparedStatementEmulation();
        turnOffMysqlStictMode();
    }

    public function index(FilterProfitReportRequest $request)
    {
        abort_if(authUser()->cannot('Read Profit Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $categories = ProductCategory::orderBy('name')->get();

        $products = Product::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        $profitReport = new ProfitReport($request->validated());

        return view('reports.profit', compact('warehouses', 'profitReport', 'categories', 'products', 'brands'));
    }
}
