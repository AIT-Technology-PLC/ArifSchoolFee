<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Services\Integrations\PointOfSaleService;
use App\Services\Models\SaleService;
use Barryvdh\DomPDF\Facade\Pdf;

class SaleController extends Controller
{
    private $saleService;

    public function __construct(SaleService $saleService)
    {
        $this->middleware('isFeatureAccessible:Sale Management');

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

        [$isExecuted, $fsNumber] = (new PointOfSaleService)->getFsNumber($sale);

        if ($isExecuted && is_numeric($fsNumber) && is_null($sale->fs_number)) {
            $sale->update(['fs_number' => $fsNumber]);
        }

        $sale->load(['saleDetails.product', 'saleDetails.merchandiseBatch', 'customer', 'contact', 'warehouse', 'company', 'createdBy', 'approvedBy']);

        $havingCode = $sale->saleDetails()->with('product')->get()->pluck('product')->pluck('code')->filter()->isNotEmpty();

        $havingBatch = $sale->saleDetails()->with('merchandiseBatch')->get()->pluck('merchandiseBatch')->pluck('batch_no')->filter()->isNotEmpty();

        return Pdf::loadView('sales.print', compact('sale', 'havingCode', 'havingBatch'))->stream();
    }
}
