<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\AttendanceDetail;

class AttendanceDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Attendance Management');
    }

    public function destroy(AttendanceDetail $attendanceDetail)
    {
        $this->authorize('delete', $attendanceDetail->attendance);

        if ($attendanceDetail->attendance->isApproved()) {
            return back()->with('failedMessage', 'You can not delete an attendance that is approved.');
        }

        $attendanceDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}