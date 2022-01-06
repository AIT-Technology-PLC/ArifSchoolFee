<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Events\TransferApprovedEvent;
use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Models\Transfer;
use App\Notifications\TransferMade;
use App\Services\TransferService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class TransferController extends Controller
{
    private $transferService;

    public function __construct(TransferService $transferService)
    {
        $this->middleware('isFeatureAccessible:Transfer Management');

        $this->middleware('isFeatureAccessible:Siv Management')->only('convertToSiv');

        $this->transferService = $transferService;
    }

    public function approve(Transfer $transfer, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $transfer);

        [$isExecuted, $message] = $action->execute($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        event(new TransferApprovedEvent($transfer));

        return back()->with('successMessage', $message);
    }

    public function subtract(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        [$isExecuted, $message] = $this->transferService->subtract($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::branch('Read Transfer', $transfer->transferred_to, $transfer->createdBy),
            new TransferMade($transfer)
        );

        return back();
    }

    public function add(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        [$isExecuted, $message] = $this->transferService->add($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::branch('Read Transfer', $transfer->transferred_from, $transfer->createdBy),
            new TransferMade($transfer)
        );

        return back();
    }

    public function convertToSiv(Transfer $transfer)
    {
        $this->authorize('view', $transfer);

        $this->authorize('create', Siv::class);

        [$isExecuted, $message, $siv] = $this->transferService->convertToSiv($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sivs.show', $siv->id);
    }

    public function close(Transfer $transfer)
    {
        $this->authorize('close', $transfer);

        [$isExecuted, $message] = $this->transferService->close($transfer);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', 'Transfer closed and archived successfully.');
    }
}
