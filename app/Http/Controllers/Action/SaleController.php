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

        if ($isExecuted && is_null($sale->fs_number)) {
            $sale->update(['fs_number' => $fsNumber]);
        }

        $sale->load(['saleDetails.product', 'customer', 'warehouse', 'company', 'createdBy', 'approvedBy']);

        return Pdf::loadView('sales.print', compact('sale'))->stream();
    }
}
