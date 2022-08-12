<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Advancement;
use App\Models\User;
use App\Notifications\AdvancementApproved;
use App\Services\Models\AdvancementService;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class AdvancementController extends Controller
{
    private $advancementService;

    public function __construct(AdvancementService $advancementService)
    {
        $this->middleware('isFeatureAccessible:Advancement Management');

        $this->advancementService = $advancementService;
    }

    public function approve(Advancement $advancement)
    {
        $this->authorize('approve', $advancement);

        if (!authUser()->hasWarehousePermission('hr', User::whereHas('employee', fn($q) => $q->whereIn('id', $advancement->advancementDetails->pluck('employee_id')))->pluck('warehouse_id'))) {
            return back()->with('failedMessage', 'You do not have permission to approve this advancement request.');
        }

        [$isExecuted, $message] = $this->advancementService->approve($advancement);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Advancement', $advancement->warehouse_id, $advancement->createdBy),
            new AdvancementApproved($advancement)
        );

        return back()->with('successMessage', $message);
    }
}
