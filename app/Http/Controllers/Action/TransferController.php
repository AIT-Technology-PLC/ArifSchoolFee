<?php

namespace App\Http\Controllers\Action;

use App\Actions\ConvertToSivAction;
use App\Http\Controllers\Controller;
use App\Models\Siv;
use App\Models\Transfer;
use App\Notifications\TransferMade;
use App\Services\InventoryOperationService;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TransferController extends Controller
{
    use NotifiableUsers, ApproveInventory;

    private $permission;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Transfer Management');

        $this->permission = 'Make Transfer';
    }

    public function transfer(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        abort_if(
            !$transfer->isSubtracted() && auth()->user()->warehouse_id != $transfer->transferred_from,
            403
        );

        abort_if(
            $transfer->isSubtracted() && !auth()->user()->addWarehouses()->contains($transfer->transferred_to),
            403
        );

        if (!$transfer->isApproved()) {
            return back()->with('failedMessage', 'This Transfer is not approved');
        }

        $result = DB::transaction(function () use ($transfer) {
            $result = InventoryOperationService::transfer($transfer->transferDetails, $transfer->isSubtracted());

            if (!$result['isTransferred']) {
                DB::rollBack();

                return $result;
            }

            $transfer->isSubtracted() ? $transfer->add() : $transfer->subtract();

            Notification::send(
                $this->notifiableUsers('Approve Transfer', $transfer->createdBy),
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
