<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateTransactionStatusRequest;
use App\Models\PadPermission;
use App\Models\Transaction;
use App\Models\Warehouse;
use App\Notifications\TransactionAdded;
use App\Notifications\TransactionApproved;
use App\Notifications\TransactionStatusUpdated;
use App\Notifications\TransactionSubtracted;
use App\Services\Models\TransactionService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Notification;

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

        Notification::send(
            PadPermission::with('users')->where('name', 'Read ' . $transaction->pad->name)->get()->pluck('users')->push($transaction->createdBy)->unique()->where('id', '!=', auth()->id())->whereIn('warehouse_id', Warehouse::whereIn('name', $transaction->transactionDetails->pluck('warehouse'))->pluck('id')),
            new TransactionApproved($transaction)
        );

        return back()->with('successMessage', $message);
    }

    public function subtract(Transaction $transaction)
    {
        $this->authorize('subtract', $transaction);

        [$isExecuted, $message] = $this->transactionService->subtract($transaction, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            PadPermission::with('users')->where('name', 'Read ' . $transaction->pad->name)->get()->pluck('users')->push($transaction->createdBy)->unique()->where('id', '!=', auth()->id())->whereIn('warehouse_id', Warehouse::whereIn('name', $transaction->transactionDetails->pluck('warehouse'))->pluck('id')),
            new TransactionSubtracted($transaction)
        );

        return back()->with('successMessage', '$message');
    }

    public function add(Transaction $transaction)
    {
        $this->authorize('add', $transaction);

        [$isExecuted, $message] = $this->transactionService->add($transaction, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            PadPermission::with('users')->where('name', 'Read ' . $transaction->pad->name)->get()->pluck('users')->push($transaction->createdBy)->unique()->where('id', '!=', auth()->id())->whereIn('warehouse_id', Warehouse::whereIn('name', $transaction->transactionDetails->pluck('warehouse'))->pluck('id')),
            new TransactionAdded($transaction)
        );

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

        if ($transaction->transactionDetails->isEmpty()) {
            return back()->with('failedMessage', 'This transaction is not applicable for printing.');
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

    public function updateStatus(Transaction $transaction, UpdateTransactionStatusRequest $request)
    {
        $this->authorize('update', $transaction);

        $transaction->status = $request->validated('status');
        $transaction->save();

        Notification::send(
            PadPermission::with('users')->where('name', 'Read ' . $transaction->pad->name)->get()->pluck('users')->push($transaction->createdBy)->unique()->where('id', '!=', auth()->id())->whereIn('warehouse_id', Warehouse::whereIn('name', $transaction->transactionDetails->pluck('warehouse'))->pluck('id')),
            new TransactionStatusUpdated($transaction)
        );

        return back()->with('successMessage', 'Status updated to ' . $transaction->status);
    }
}
