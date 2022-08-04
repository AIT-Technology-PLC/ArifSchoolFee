<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use App\Services\Models\TransactionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;

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
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function subtract(Transaction $transaction)
    {
        $this->authorize('subtract', $transaction);

        [$isExecuted, $message] = $this->transactionService->subtract($transaction, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function add(Transaction $transaction)
    {
        $this->authorize('add', $transaction);

        [$isExecuted, $message] = $this->transactionService->add($transaction, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function printed(Transaction $transaction)
    {
        $this->authorize('view', $transaction);

        if (!$transaction->pad->isPrintable()) {
            return back()->with('failedMessage', 'This transaction is not printable.');
        }

        if ($transaction->pad->isApprovable() && !$transaction->isApproved()) {
            return back()->with('failedMessage', 'This transaction is not approved yet.');
        }

        $columns = array_keys($transaction->transactionDetails->first());

        Arr::forget($columns, [0, 1]);

        return Pdf::loadView('transactions.print', compact('transaction', 'columns'))->stream();
    }

    public function convertTo(Transaction $transaction)
    {
        $this->authorize('convert', $transaction);

        [$route, $data] = $this->transactionService->convertTo($transaction, request('target'));

        return redirect($route)->withInput($data);
    }

    public function convertFrom(Transaction $transaction)
    {
        $this->authorize('convert', $transaction);

        [$route, $data] = $this->transactionService->convertFrom($transaction, request('target'), request('id'));

        return redirect($route)->withInput($data);
    }
}
