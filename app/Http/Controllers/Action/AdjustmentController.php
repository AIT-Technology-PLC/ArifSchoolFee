<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Adjustment;
use App\Notifications\AdjustmentMade;
use App\Services\InventoryOperationService;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdjustmentController extends Controller
{
    use NotifiableUsers, ApproveInventory;

    private $permission;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Adjustment');

        $this->permission = 'Make Adjustment';
    }

    public function adjust(Adjustment $adjustment)
    {
        $this->authorize('adjust', $adjustment);

        if (!$adjustment->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This Adjustment is not approved');
        }

        $result = DB::transaction(function () use ($adjustment) {
            $result = InventoryOperationService::adjust($adjustment->adjustmentDetails);

            if (!$result['isAdjusted']) {
                DB::rollBack();

                return $result;
            }

            $adjustment->adjust();

            Notification::send(
                $this->notifiableUsers('Approve Adjustment', $adjustment->createdBy),
                new AdjustmentMade($adjustment)
            );

            return $result;
        });

        return $result['isAdjusted'] ?
        redirect()->back() :
        redirect()->back()->with('failedMessage', $result['unavailableProducts']);
    }
}
