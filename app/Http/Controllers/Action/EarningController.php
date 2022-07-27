<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Earning;
use App\Notifications\EarningApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class EarningController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Earning Management');
    }

    public function approve(Earning $earning, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $earning);

        [$isExecuted, $message] = $action->execute($earning, EarningApproved::class);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Earning', $earning->createdBy),
            new EarningApproved($earning)
        );

        return back()->with('successMessage', $message);
    }
}
