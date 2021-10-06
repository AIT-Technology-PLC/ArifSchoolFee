<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Adjustment;
use App\Notifications\AdjustmentApproved;
use App\Notifications\AdjustmentMade;
use App\Services\InventoryOperationService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AdjustmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Inventory Adjustment');
    }

    public function approve(Adjustment $adjustment, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $adjustment);

        [$isExecuted, $message] = $action->execute($adjustment, AdjustmentApproved::class, 'Make Adjustment');

        if (!$isExecuted) {
            return redirect()->back()->with('failedMessage', $message);
        }

        return redirect()->back()->with('successMessage', $message);
    }

    public function adjust(Adjustment $adjustment)
    {
        $this->authorize('adjust', $adjustment);

        if (!$adjustment->isApproved()) {
            return back()->with('failedMessage', 'This Adjustment is not approved');
        }

        $result = DB::transaction(function () use ($adjustment) {
            $result = InventoryOperationService::adjust($adjustment->adjustmentDetails);

            if (!$result['isAdjusted']) {
                DB::rollBack();

                return $result;
            }

            $adjustment->adjust();

            Notification::send(
                notifiables('Approve Adjustment', $adjustment->createdBy),
                new AdjustmentMade($adjustment)
            );

            return $result;
        });

        return $result['isAdjusted'] ?
        back() :
        back()->with('failedMessage', $result['unavailableProducts']);
    }
}
