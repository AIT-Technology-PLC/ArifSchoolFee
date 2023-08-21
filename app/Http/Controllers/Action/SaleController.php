<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\Siv;
use App\Services\Models\SaleService;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    private $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->middleware('isFeatureAccessible:Sale Management');

        $this->middleware('isFeatureAccessible:Credit Management')->only('convertToCredit');

        $this->middleware('isFeatureAccessible:Siv Management')->only('convertToSiv');

        $this->saleService = $saleService;
    }

    public function approve(Sale $sale)
    {
        $this->authorize('approve', $sale);

        [$isExecuted, $message] = $this->saleService->approve($sale);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function cancel(Sale $sale)
    {
        if ($sale->warehouse->hasPosIntegration()) {
            back()->with('failedMessage', 'Subtracting is not allowed.');
        }

        $this->authorize('cancel', $sale);

        [$isExecuted, $message] = $this->saleService->cancel($sale);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function printed(Sale $sale)
    {
        $this->authorize('view', $sale);

        $sale->load(['saleDetails.product', 'saleDetails.merchandiseBatch', 'customer', 'contact', 'warehouse', 'company', 'createdBy', 'approvedBy']);

        $havingCode = $sale->saleDetails()->with('product')->get()->pluck('product')->pluck('code')->filter()->isNotEmpty();

        $havingBatch = $sale->saleDetails()->with('merchandiseBatch')->get()->pluck('merchandiseBatch')->pluck('batch_no')->filter()->isNotEmpty();

        return Pdf::loadView('sales.print', compact('sale', 'havingCode', 'havingBatch'))->stream();
    }

    public function convertToCredit(Sale $sale)
    {
        authUser()->can('Convert To Credit');

        [$isExecuted, $message] = $this->saleService->convertToCredit($sale);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('credits.show', $sale->credit->id);
    }

    public function subtract(Sale $sale)
    {
        $this->authorize('subtract', $sale);

        [$isExecuted, $message] = $this->saleService->subtract($sale, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }

    public function approveAndSubtract(Sale $sale)
    {
        $this->authorize('approve', $sale);

        $this->authorize('subtract', $sale);

        [$isExecuted, $message] = $this->saleService->approveAndSubtract($sale, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }

    public function convertToSiv(Sale $sale)
    {
        $this->authorize('create', Siv::class);

        [$isExecuted, $message, $siv] = $this->saleService->convertToSiv($sale, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sivs.show', $siv->id);
    }
}
