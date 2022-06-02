<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\SaleDatatable;
use App\DataTables\SaleDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Sale;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sale Management');

        $this->authorizeResource(Sale::class, 'sale');
    }

    public function index(SaleDatatable $datatable)
    {
        $datatable->builder()->setTableId('sales-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalSales = Sale::count();

        $totalNotApproved = Sale::notApproved()->notCancelled()->count();

        $totalApproved = Sale::approved()->notCancelled()->count();

        $totalCancelled = Sale::cancelled()->count();

        return $datatable->render('sales.index', compact('totalSales', 'totalNotApproved', 'totalApproved', 'totalCancelled'));
    }

    public function create()
    {
        $currentReceiptNo = nextReferenceNumber('sales');

        return view('sales.create', compact('currentReceiptNo'));
    }

    public function store(StoreSaleRequest $request)
    {
        $sale = DB::transaction(function () use ($request) {
            $sale = Sale::create($request->except('sale'));

            $sale->saleDetails()->createMany($request->sale);

            return $sale;
        });

        return redirect()->route('sales.show', $sale->id);
    }

    public function show(Sale $sale, SaleDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('sale-details-datatable');

        $sale->load(['saleDetails.product', 'gdns', 'customer']);

        return $datatable->render('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('saleDetails.product');

        return view('sales.edit', compact('sale'));
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        DB::transaction(function () use ($request, $sale) {
            $sale->update($request->except('sale'));

            for ($i = 0; $i < count($request->sale); $i++) {
                $sale->saleDetails[$i]->update($request->sale[$i]);
            }
        });

        return redirect()->route('sales.show', $sale->id);
    }

    public function destroy(Sale $sale)
    {
        $sale->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
