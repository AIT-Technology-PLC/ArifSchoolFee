<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Actions\CancelTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Leave;
use App\Notifications\LeaveApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class LeaveController extends Controller
{

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Leave Management');
    }

    public function approve(Leave $leaf, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $leaf);

        [$isExecuted, $message] = $action->execute($leaf);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Leave', $leaf->createdBy)->push($leaf->employee->user),
            new LeaveApproved($leaf)
        );

        return back()->with('successMessage', $message);
    }

    public function cancel(Leave $leaf, CancelTransactionAction $action)
    {
        $this->authorize('cancel', $leaf);

        [$isExecuted, $message] = $action->execute($leaf);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }
}