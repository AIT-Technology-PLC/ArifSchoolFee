<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\AnnouncementDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\Announcement;

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

    }

    public function store(StoreAnnouncementRequest $request)
    {

    }

    public function show($id)
    {

    }

    public function edit(Announcement $announcement)
    {
        if ($announcement->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an announcement that is approved.');
        }

        //
        return view('announcements.edit', compact());
    }

    public function update(UpdateAnnouncementRequest $request, Announcement $announcement)
    {
        if ($announcement->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an announcement that is approved.');
        }

        //
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
