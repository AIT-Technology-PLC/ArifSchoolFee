<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\AttendanceDatatable;
use App\DataTables\AttendanceDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreAttendanceRequest;
use App\Http\Requests\UpdateAttendanceRequest;
use App\Models\Attendance;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Attendance Management');

        $this->authorizeResource(Attendance::class);
    }

    public function index(AttendanceDatatable $datatable)
    {
        $datatable->builder()->setTableId('attendances-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalAttendances = Attendance::count();

        $totalApproved = Attendance::approved()->notCancelled()->count();

        $totalNotApproved = Attendance::notApproved()->notCancelled()->count();

        $totalCancelled = Attendance::cancelled()->count();

        return $datatable->render('attendances.index', compact('totalAttendances', 'totalApproved', 'totalNotApproved', 'totalCancelled'));
    }

    public function create()
    {
        $currentAttendanceCode = nextReferenceNumber('attendances');

        $users = User::getUsers();

        return view('attendances.create', compact('currentAttendanceCode', 'users'));
    }

    public function store(StoreAttendanceRequest $request)
    {
        $attendance = DB::transaction(function () use ($request) {
            $attendance = Attendance::create($request->safe()->except('attendance'));

            if (count($request->safe()->only('attendance'))) {
                $attendance->attendanceDetails()->createMany($request->validated('attendance'));
            }

            return $attendance;
        });

        return redirect()->route('attendances.show', $attendance->id);
    }

    public function show(Attendance $attendance, AttendanceDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('attendance-details-datatable');

        return $datatable->render('attendances.show', compact('attendance'));
    }

    public function edit(Attendance $attendance)
    {
        if ($attendance->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an attendance that is approved.');
        }

        if ($attendance->isCancelled()) {
            return back()->with('failedMessage', 'You can not modify an attendance that is cancelled.');
        }

        $users = User::getUsers();

        $attendance->load(['attendanceDetails']);

        return view('attendances.edit', compact('attendance', 'users'));
    }

    public function update(UpdateAttendanceRequest $request, Attendance $attendance)
    {
        if ($attendance->isApproved()) {
            return back()->with('failedMessage', 'You can not modify an attendance that is approved.');
        }

        if ($attendance->isCancelled()) {
            return back()->with('failedMessage', 'You can not modify an attendance that is cancelled.');
        }

        DB::transaction(function () use ($request, $attendance) {
            $attendance->update($request->safe()->except('attendance'));

            $attendance->attendanceDetails()->forceDelete();

            if (count($request->safe()->only('attendance'))) {
                $attendance->attendanceDetails()->createMany($request->validated('attendance'));
            }

        });

        return redirect()->route('attendances.show', $attendance->id);
    }

    public function destroy(Attendance $attendance)
    {
        if ($attendance->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an attendance that is approved.');
        }

        if ($attendance->isCancelled()) {
            return back()->with('failedMessage', 'You can not delete an attendance that is cancelled.');
        }

        $attendance->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}