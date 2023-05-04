<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTransactionStatusRequest;
use App\Models\Transaction;
use App\Notifications\TransactionAdded;
use App\Notifications\TransactionApproved;
use App\Notifications\TransactionStatusUpdated;
use App\Notifications\TransactionSubtracted;
use App\Services\Models\TransactionService;
use App\Utilities\Notifiables;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Notification;

class TransactionController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function approve(Transaction $transaction)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->authorize('approve', $transaction);

        [$isExecuted, $message] = $this->transactionService->approve($transaction);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::forPad($transaction->pad, $transaction->createdBy),
            new TransactionApproved($transaction)
        );

        return back()->with('successMessage', $message);
    }

    public function subtract(Transaction $transaction)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->authorize('subtract', $transaction);

        [$isExecuted, $message] = $this->transactionService->subtract($transaction, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::forPad($transaction->pad, $transaction->createdBy),
            new TransactionSubtracted($transaction)
        );

        return back()->with('successMessage', $message);
    }

    public function add(Transaction $transaction)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->authorize('add', $transaction);

        [$isExecuted, $message] = $this->transactionService->add($transaction, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::forPad($transaction->pad, $transaction->createdBy),
            new TransactionAdded($transaction)
        );

        return back()->with('successMessage', $message);
    }

    public function printed(Transaction $transaction)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $columns['detail'] = [];
        $columns['master'] = [];

        $this->authorize('view', $transaction);

        if (!$transaction->pad->isPrintable()) {
            return back()->with('failedMessage', 'This transaction is not printable.');
        }

        if ($transaction->pad->isApprovable() && !$transaction->isApproved()) {
            return back()->with('failedMessage', 'This transaction is not approved yet.');
        }

        if ($transaction->transactionDetails->isNotEmpty()) {
            $columns['detail'] = $transaction->pad->padFields()->detailFields()->printable()->pluck('label')->map(fn($label) => str()->snake($label))->toArray();
        }

        if (count($columns['detail']) && array_search('batch', $columns['detail'])) {
            $columns['detail'][] = 'expires_on';
        }

        if ($transaction->transactionMasters->isNotEmpty()) {
            $columns['master'] = $transaction->pad->padFields()->masterFields()->printable()->pluck('label')->map(fn($label) => str()->snake($label))->toArray();
        }

        return Pdf::loadView('transactions.print', compact('transaction', 'columns'))->stream();
    }

    public function convertTo(Transaction $transaction)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->authorize('convert', $transaction);

        [$route, $data] = $this->transactionService->convertTo($transaction, request('target'));

        return redirect($route)->withInput($data);
    }

    public function convertFrom(Transaction $transaction)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->authorize('convert', $transaction);

        [$route, $data] = $this->transactionService->convertFrom($transaction, request('target'), request('id'));

        return redirect($route)->withInput($data);
    }

    public function updateStatus(Transaction $transaction, UpdateTransactionStatusRequest $request)
    {
        abort_if(!$transaction->pad->isEnabled(), 403);

        $this->authorize('updateStatus', $transaction);

        $transaction->status = $request->validated('status');
        $transaction->save();

        Notification::send(
            Notifiables::forPad($transaction->pad, $transaction->createdBy),
            new TransactionStatusUpdated($transaction)
        );

        return back()->with('successMessage', 'Status updated to ' . $transaction->status);
    }
}
