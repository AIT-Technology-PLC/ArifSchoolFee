<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Models\Announcement;
use App\Notifications\AnnouncementApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Announcement Management');
    }

    public function approve(Announcement $announcement, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $announcement);

        [$isExecuted, $message] = $action->execute($announcement, AnnouncementApproved::class);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(
            Notifiables::byPermissionAndWarehouse('Read Announcement', $announcement->createdBy),
            new AnnouncementApproved($announcement)
        );

        return back()->with('successMessage', $message);
    }
}
