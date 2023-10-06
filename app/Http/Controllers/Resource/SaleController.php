<?php

namespace App\Http\Controllers\Resource;

use App\Actions\AutoBatchStoringAction;
use App\DataTables\SaleDatatable;
use App\DataTables\SaleDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Sale;
use App\Notifications\SalePrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

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

        $totalApproved = Sale::notSubtracted()->notCancelled()->approved()->count();

        $totalSubtracted = Sale::subtracted()->notCancelled()->count();

        $totalCancelled = Sale::cancelled()->count();

        return $datatable->render('sales.index', compact('totalSales', 'totalNotApproved', 'totalApproved', 'totalSubtracted', 'totalCancelled'));
    }

    public function create()
    {
        $currentInvoiceNo = nextReferenceNumber('sales');

        $warehouses = authUser()->getAllowedWarehouses('sales');

        return view('sales.create', compact('currentInvoiceNo', 'warehouses'));
    }

    public function store(StoreSaleRequest $request)
    {
        $sale = DB::transaction(function () use ($request) {
            $sale = Sale::create($request->safe()->except('sale'));

            $sale->saleDetails()->createMany($request->validated('sale'));

            AutoBatchStoringAction::execute($sale, $request->validated('sale'), 'saleDetails');

            $sale->createCustomFields($request->validated('customField'));

            Notification::send(Notifiables::byNextActionPermission('Approve Sale'), new SalePrepared($sale));

            return $sale;
        });

        return redirect()->route('sales.show', $sale->id);
    }

    public function show(Sale $sale, SaleDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('sale-details-datatable');

        $sale->load(['saleDetails.product', 'saleDetails.warehouse', 'saleDetails.merchandiseBatch', 'gdns', 'customer', 'contact', 'customFieldValues.customField']);

        return $datatable->render('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        if ($sale->isApproved() || $sale->isCancelled()) {
            return back()->with('failedMessage', 'Invoices that are approved/cancelled can not be edited.');
        }

        $warehouses = authUser()->getAllowedWarehouses('sales');

        $sale->load('saleDetails.product', 'saleDetails.warehouse', 'saleDetails.merchandiseBatch');

        return view('sales.edit', compact('sale', 'warehouses'));
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        if ($sale->isApproved() || $sale->isCancelled()) {
            return back()->with('failedMessage', 'Invoices that are approved/cancelled can not be edited.');
        }

        DB::transaction(function () use ($request, $sale) {
            $sale->update($request->safe()->except('sale'));

            $sale->saleDetails()->forceDelete();

            $sale->saleDetails()->createMany($request->validated('sale'));

            AutoBatchStoringAction::execute($sale, $request->validated('sale'), 'saleDetails');

            $sale->createCustomFields($request->validated('customField'));
        });

        return redirect()->route('sales.show', $sale->id);
    }

    public function destroy(Sale $sale)
    {
        abort_if($sale->isApproved() || $sale->isCancelled(), 403);

        $sale->proformaInvoice?->dissociated();

        $sale->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
