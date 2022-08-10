<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Warning;
use App\Notifications\WarningApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class WarningController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Warning Management');
    }

    public function approve(Warning $warning, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $warning);

        if (!authUser()->hasWarehousePermission('hr', $warning->warehouse_id)) {
            return back()->with('failedMessage', 'You do not have permission to approve this warning request.');
        }

        [$isExecuted, $message] = $action->execute($warning, WarningApproved::class);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Warning', $warning->warehouse_id, $warning->createdBy)->push($warning->employee->user),
            new WarningApproved($warning)
        );

        return back()->with('successMessage', $message);
    }
}
