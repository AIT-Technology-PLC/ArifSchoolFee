<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Models\TransactionService;

class TransactionController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->middleware('isFeatureAccessible:Pad Management');

        $this->transactionService = $transactionService;
    }

    public function approve(Transaction $transaction)
    {
        $this->authorize('approve', $transaction);

        [$isExecuted, $message] = $this->transactionService->approve($transaction);

        if (!$isExecuted) {
            back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function subtract(Transaction $transaction)
    {
        $this->authorize('subtract', $transaction);

        [$isExecuted, $message] = $this->transactionService->subtract($transaction, auth()->user());

        if (!$isExecuted) {
            back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function add(Transaction $transaction)
    {
        $this->authorize('add', $transaction);

        [$isExecuted, $message] = $this->transactionService->add($transaction, auth()->user());

        if (!$isExecuted) {
            back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function close(Transaction $transaction)
    {
        $this->authorize('close', $transaction);

        [$isExecuted, $message] = $this->transactionService->close($transaction);

        if (!$isExecuted) {
            back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function cancel(Transaction $transaction)
    {
        $this->authorize('cancel', $transaction);

        [$isExecuted, $message] = $this->transactionService->cancel($transaction);

        if (!$isExecuted) {
            back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }
}
