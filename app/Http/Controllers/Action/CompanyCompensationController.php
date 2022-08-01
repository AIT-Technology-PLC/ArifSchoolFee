<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\CompanyCompensation;
use App\Notifications\CompensationApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class CompanyCompensationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Compensation Management');
    }

    public function approve(CompanyCompensation $companyCompensation, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $companyCompensation);

        [$isExecuted, $message] = $action->execute($companyCompensation, CompensationApproved::class);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Compensation', $companyCompensation->createdBy),
            new CompensationApproved($companyCompensation)
        );

        return back()->with('successMessage', $message);
    }
}