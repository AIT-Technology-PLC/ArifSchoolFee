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
            Notifiables::byPermissionAndWarehouse('Read Announcement', $announcement->warehouses()->pluck('id'), $announcement->createdBy),
            new AnnouncementApproved($announcement)
        );

        return back()->with('successMessage', $message);
    }

    public function board(Announcement $announcement)
    {
        $this->authorize('viewAny', $announcement);

        $announcements = Announcement::query()
            ->when(request('sort') == 'latest', fn($query) => $query->orderBy('created_at', 'DESC'))
            ->when(request('sort') == 'oldest', fn($query) => $query->orderBy('created_at', 'ASC'))
            ->simplePaginate(5);

        return view('announcements.board', compact('announcements'));
    }
}
