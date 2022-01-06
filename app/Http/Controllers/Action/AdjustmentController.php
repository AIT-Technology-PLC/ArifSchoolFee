<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Adjustment;
use App\Notifications\AdjustmentApproved;
use App\Notifications\AdjustmentMade;
use App\Services\AdjustmentService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class AdjustmentController extends Controller
{
    private $adjustmentService;

    public function __construct(AdjustmentService $adjustmentService)
    {
        $this->middleware('isFeatureAccessible:Inventory Adjustment');

        $this->adjustmentService = $adjustmentService;
    }

    public function approve(Adjustment $adjustment, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $adjustment);

        [$isExecuted, $message] = $action->execute($adjustment, AdjustmentApproved::class, 'Make Adjustment');

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function adjust(Adjustment $adjustment)
    {
        $this->authorize('adjust', $adjustment);

        [$isExecuted, $message] = $this->adjustmentService->adjust($adjustment);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::branch('Read Adjustment', $adjustment->adjustmentDetails->pluck('warehouse_id'), $adjustment->createdBy),
            new AdjustmentMade($adjustment)
        );

        return back();
    }
}
