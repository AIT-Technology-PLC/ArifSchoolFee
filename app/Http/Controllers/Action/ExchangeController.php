<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Exchange;
use App\Services\Models\ExchangeService;
use Barryvdh\DomPDF\Facade\Pdf;

class ExchangeController extends Controller
{
    private $exchangeService;

    public function __construct(ExchangeService $exchangeService)
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->middleware('isFeatureAccessible:Sale Management');

        $this->middleware('isFeatureAccessible:Exchange Management');

        $this->exchangeService = $exchangeService;
    }

    public function approve(Exchange $exchange)
    {
        $this->authorize('approve', $exchange);

        [$isExecuted, $message] = $this->exchangeService->approve($exchange);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function execute(Exchange $exchange)
    {
        $this->authorize('execute', $exchange);

        [$isExecuted, $message] = $this->exchangeService->execute($exchange, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function approveAndExecute(Exchange $exchange)
    {
        $this->authorize('approve', $exchange);

        $this->authorize('execute', $exchange);

        [$isExecuted, $message] = $this->exchangeService->approveAndExecute($exchange, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function printed(Exchange $exchange)
    {
        $this->authorize('view', $exchange);

        if (!$exchange->isApproved()) {
            return back()->with('failedMessage', 'This Exchange is not approved yet.');
        }

        if (!$exchange->isExecuted()) {
            return back()->with('failedMessage', 'This Exchange is not executed yet.');
        }

        $exchange->load(['exchangeDetails.product', 'returnn', 'returnn.returnDetails', 'exchangeDetails.merchandiseBatch', 'createdBy', 'approvedBy']);

        $havingBatch = $exchange->exchangeDetails()->with('merchandiseBatch')->get()->pluck('merchandiseBatch')->pluck('batch_no')->filter()->isNotEmpty();

        return Pdf::loadView('exchanges.print', compact('exchange', 'havingBatch'))->stream();
    }
}
