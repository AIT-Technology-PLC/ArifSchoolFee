<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\Advancement;
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

        [$isExecuted, $message] = $this->advancementService->approve($advancement);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Advancement', $advancement->createdBy),
            new AdvancementApproved($advancement)
        );

        return back()->with('successMessage', $message);
    }
}
