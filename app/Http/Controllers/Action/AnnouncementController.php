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
            Notifiables::byPermissionAndWarehouse('Read Announcement', $announcement->warehouses()->pluck('warehouses.id'), $announcement->createdBy),
            new AnnouncementApproved($announcement)
        );

        return back()->with('successMessage', $message);
    }

    public function board(Announcement $announcement)
    {
        $this->authorize('viewAny', $announcement);

        $announcements = Announcement::query()
            ->approved()
            ->with(['createdBy', 'approvedBy'])
            ->when(request('sort') == 'latest', fn($query) => $query->orderBy('created_at', 'DESC'))
            ->when(request('sort') == 'oldest', fn($query) => $query->orderBy('created_at', 'ASC'))
            ->when(request('period') == 'today', fn($query) => $query->whereDate('created_at', today()))
            ->when(request('period') == 'this week', fn($query) => $query->whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()]))
            ->when(request('period') == 'this month', fn($query) => $query->whereMonth('created_at', today()->month)->whereYear('created_at', today()->year))
            ->simplePaginate(5);

        $totalAnnouncementsToday = Announcement::whereDate('created_at', today())->count();

        $totalAnnouncementsThisWeek = Announcement::whereBetween('created_at', [now()->startOfWeek(), now()->endOfWeek()])->count();

        $totalAnnouncementsThisMonth = Announcement::whereMonth('created_at', today()->month)->whereYear('created_at', today()->year)->count();

        return view('announcements.board', compact('announcements', 'totalAnnouncementsToday', 'totalAnnouncementsThisWeek', 'totalAnnouncementsThisMonth'));
    }
}
