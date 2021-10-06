<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\ConvertToSivAction;
use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Models\Transfer;
use App\Notifications\TransferApproved;
use App\Notifications\TransferMade;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
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
            return redirect()->back()->with('failedMessage', $message);
        }

        return redirect()->back()->with('successMessage', $message);
    }

    public function transfer(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        if (!$transfer->isApproved()) {
            return back()->with('failedMessage', 'This Transfer is not approved');
        }

        $result = DB::transaction(function () use ($transfer) {
            $result = InventoryOperationService::transfer($transfer->transferDetails, $transfer->isSubtracted());

            if (!$result['isTransferred']) {
                DB::rollBack();

                return $result;
            }

            $transfer->transfer();

            Notification::send(
                notifiables('Approve Transfer', $transfer->createdBy),
                new TransferMade($transfer)
            );

            return $result;
        });

        return $result['isTransferred'] ? back() :
        back()->with('failedMessage', $result['unavailableProducts']);
    }

    public function convertToSiv(Transfer $transfer, ConvertToSivAction $action)
    {
        $this->authorize('view', $transfer);

        $this->authorize('create', Siv::class);

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
}
