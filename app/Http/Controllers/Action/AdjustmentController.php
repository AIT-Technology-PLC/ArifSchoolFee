<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Adjustment;
use App\Notifications\AdjustmentApproved;
use App\Notifications\AdjustmentMade;
use App\Services\AdjustmentService;
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
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function adjust(Adjustment $adjustment, AdjustmentService $adjustmentService)
    {
        $this->authorize('adjust', $adjustment);

        [$isExecuted, $message] = $adjustmentService->adjust($adjustment);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(notifiables('Approve Adjustment', $adjustment->createdBy), new AdjustmentMade($adjustment));

        return back();
    }
}
