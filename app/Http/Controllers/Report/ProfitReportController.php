<?php

namespace App\Http\Controllers\Report;

use App\Http\Controllers\Controller;
use App\Http\Requests\FilterProfitReportRequest;
use App\Models\Brand;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Reports\ProfitReport;
use App\Reports\SaleReport;

class ProfitReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Profit Report');
    }

    public function index(FilterProfitReportRequest $request)
    {
        abort_if(authUser()->cannot('Read Profit Report'), 403);

        $warehouses = authUser()->getAllowedWarehouses('transactions');

        $categories = ProductCategory::orderBy('name')->get();

        $products = Product::orderBy('name')->get();

        $brands = Brand::orderBy('name')->get();

        $profitReports = new ProfitReport($request->validated());

        $saleReport = new SaleReport($request->validated());

        return view('reports.profit', compact('warehouses', 'saleReport', 'profitReports', 'categories', 'products', 'brands'));
    }
}
