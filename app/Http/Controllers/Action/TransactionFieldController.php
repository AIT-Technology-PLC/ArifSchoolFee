<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\PadPermission;
use App\Models\TransactionField;
use App\Models\Warehouse;
use App\Notifications\TransactionProductAdded;
use App\Notifications\TransactionProductSubtracted;
use App\Services\Models\TransactionService;
use Illuminate\Support\Facades\Notification;

class TransactionFieldController extends Controller
{
    private $transactionService;

    public function __construct(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function subtract(TransactionField $transactionField)
    {
        abort_if(!$transactionField->transaction->pad->isEnabled(), 403);

        $this->authorize('subtract', $transactionField->transaction);

        [$isExecuted, $message] = $this->transactionService->subtract($transactionField->transaction, authUser(), $transactionField->line);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            PadPermission::with('users')->where('name', 'Read ' . $transactionField->transaction->pad->name)->get()->pluck('users')->whereIn('warehouse_id', Warehouse::whereIn('name', $transactionField->transaction->transactionDetails->pluck('warehouse'))->pluck('id'))->push($transactionField->transaction->createdBy)->unique()->where('id', '!=', auth()->id()),
            new TransactionProductSubtracted($transactionField->transaction->transactionDetails->firstWhere('line', $transactionField->line))
        );

        return back()->with('successMessage', $message);
    }

    public function add(TransactionField $transactionField)
    {
        abort_if(!$transactionField->transaction->pad->isEnabled(), 403);

        $this->authorize('add', $transactionField->transaction);

        [$isExecuted, $message] = $this->transactionService->add($transactionField->transaction, authUser(), $transactionField->line);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            PadPermission::with('users')->where('name', 'Read ' . $transactionField->transaction->pad->name)->get()->pluck('users')->whereIn('warehouse_id', Warehouse::whereIn('name', $transactionField->transaction->transactionDetails->pluck('warehouse'))->pluck('id'))->push($transactionField->transaction->createdBy)->unique()->where('id', '!=', auth()->id()),
            new TransactionProductAdded($transactionField->transaction->transactionDetails->firstWhere('line', $transactionField->line))
        );

        return back()->with('successMessage', $message);
    }
}
