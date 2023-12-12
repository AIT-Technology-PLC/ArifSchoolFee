<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Events\TransferApproved;
use App\Http\Controllers\Controller;
use App\Http\Requests\ConvertTransferToSivRequest;
use App\Models\Siv;
use App\Models\Transfer;
use App\Notifications\TransferMade;
use App\Services\Models\TransferService;
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

        event(new TransferApproved($transfer));

        return back()->with('successMessage', $message);
    }

    public function subtract(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        [$isExecuted, $message] = $this->transferService->subtract($transfer, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Transfer', $transfer->transferred_to, $transfer->createdBy),
            new TransferMade($transfer)
        );

        return back();
    }

    public function add(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        [$isExecuted, $message] = $this->transferService->add($transfer, authUser());

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Transfer', $transfer->transferred_from, $transfer->createdBy),
            new TransferMade($transfer)
        );

        return back();
    }

    public function convertToSiv(Transfer $transfer, ConvertTransferToSivRequest $request)
    {
        $this->authorize('create', Siv::class);

        [$isExecuted, $message, $siv] = $this->transferService->convertToSiv($transfer, authUser(), $request->validated());

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
