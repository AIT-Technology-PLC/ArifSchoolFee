<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\TransactionField;
use App\Services\Models\TransactionService;

class TransactionFieldController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->middleware('isFeatureAccessible:Pad Management');

        $this->transactionService = $transactionService;
    }

    public function subtract(TransactionField $transactionField)
    {
        $this->authorize('subtract', $transactionField->transaction);

        [$isExecuted, $message] = $this->transactionService->subtract($transactionField->transaction, authUser(), $transactionField->line);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function add(TransactionField $transactionField)
    {
        $this->authorize('add', $transactionField->transaction);

        [$isExecuted, $message] = $this->transactionService->add($transactionField->transaction, authUser(), $transactionField->line);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }
}
