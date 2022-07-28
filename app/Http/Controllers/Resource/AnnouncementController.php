<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\AnnouncementDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Models\Warehouse;
use App\Notifications\AnnouncementApproved;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class AnnouncementController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Announcement Management');

        $this->authorizeResource(Announcement::class);
    }

    public function index(AnnouncementDatatable $datatable)
    {
        $datatable->builder()->setTableId('announcements-datatable')->orderBy(1, 'desc');

        $totalAnnouncements = Announcement::count();

        $totalApproved = Announcement::approved()->count();

        $totalNotApproved = Announcement::notApproved()->count();

        return $datatable->render('announcements.index', compact('totalAnnouncements', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $currentAnnouncementCode = nextReferenceNumber('announcements');

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        return view('announcements.create', compact('currentAnnouncementCode', 'warehouses'));
    }

    public function store(StoreAnnouncementRequest $request)
    {
        $announcement = DB::transaction(function () use ($request) {
            $announcement = Announcement::create($request->safe()->except('warehouse_id'));

            $announcement->warehouses()->sync($request->validated('warehouse_id'));

            Notification::send(Notifiables::byNextActionPermission('Approve Announcement'), new AnnouncementApproved($announcement));

            return $announcement;
        });

        return redirect()->route('announcements.show', $announcement->id);
    }

    public function show(Announcement $announcement)
    {
        $announcement->load('warehouses');

        return view('announcements.show', compact('announcement'));
    }

    public function edit(Announcement $announcement)
    {
        if ($announcement->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an announcement that is approved.');
        }

        $warehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        return view('announcements.edit', compact('announcement', 'warehouses'));
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        if ($announcement->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an announcement that is approved.');
        }

        DB::transaction(function () use ($request, $announcement) {
            $announcement->update($request->safe()->except('announcement'));

            $announcement->warehouses()->sync($request->validated('warehouse_id'));
        });

        return redirect()->route('announcements.show', $announcement->id);
    }

    public function destroy(Announcement $announcement)
    {
        if ($announcement->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an announcement that is approved.');
        }

        $announcement->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
