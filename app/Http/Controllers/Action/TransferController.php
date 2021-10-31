<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\ConvertToSivAction;
use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Models\Transfer;
use App\Notifications\TransferApproved;
use App\Notifications\TransferMade;
use App\Services\TransferService;
use Illuminate\Support\Facades\Notification;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Transfer Management');
    }

    public function approve(Transfer $transfer, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $transfer);

        [$isExecuted, $message] = $action->execute($transfer, TransferApproved::class, 'Make Transfer');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function subtract(Transfer $transfer, TransferService $transferService)
    {
        $this->authorize('transfer', $transfer);

        [$isExecuted, $message] = $transferService->subtract($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(notifiables('Approve Transfer', $transfer->createdBy), new TransferMade($transfer));

        return back();
    }

    public function add(Transfer $transfer, TransferService $transferService)
    {
        $this->authorize('transfer', $transfer);

        [$isExecuted, $message] = $transferService->add($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(notifiables('Approve Transfer', $transfer->createdBy), new TransferMade($transfer));

        return back();
    }

    public function convertToSiv(Transfer $transfer, ConvertToSivAction $action)
    {
        $this->authorize('view', $transfer);

        $this->authorize('create', Siv::class);

        if (!$transfer->isSubtracted()) {
            return back()->with('failedMessage', 'This transfer is not subtracted yet.');
        }

        if ($transfer->isClosed()) {
            return back()->with('failedMessage', 'This transfer is already closed.');
        }

        $transferDetails = $transfer->transferDetails()->get(['product_id', 'quantity'])->toArray();

        data_fill($transferDetails, '*.warehouse_id', $transfer->transferred_from);

        $siv = $action->execute(
            'Transfer',
            $transfer->code,
            null,
            $transfer->approved_by,
            $transferDetails,
        );

        return redirect()->route('sivs.show', $siv->id);
    }

    public function close(Transfer $transfer)
    {
        $this->authorize('approve', $transfer);

        if (!$transfer->isAdded()) {
            return back()->with('failedMessage', 'This transfer is not added to destination yet.');
        }

        if ($transfer->isClosed()) {
            return back()->with('failedMessage', 'This transfer is already closed.');
        }

        $transfer->close();

        return back()->with('successMessage', 'Transfer closed and archived successfully.');
    }
}
