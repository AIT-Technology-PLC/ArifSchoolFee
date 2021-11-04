<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\ConvertToSivAction;
use App\Events\TransferApprovedEvent;
use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Models\Transfer;
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

        if (!auth()->user()->hasWarehousePermission('add', $transfer->transferred_to)) {
            return back()->with('failedMessage', 'You do not have permission to approve in one or more of the warehouses.');
        }

        [$isExecuted, $message] = $action->execute($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        event(new TransferApprovedEvent($transfer));

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

        if (!auth()->user()->hasWarehousePermission('siv', $transfer->transferred_from)) {
            return back()->with('failedMessage', 'You do not have permission to convert to one or more of the warehouses.');
        }

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

        if (!auth()->user()->hasWarehousePermission('add', $transfer->transferred_to)) {
            return back()->with('failedMessage', 'You do not have permission to close in one or more of the warehouses.');
        }

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
