<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Models\JobDetail;

class JobDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Job Management');
    }

    public function destroy(JobDetail $jobDetail)
    {
        $this->authorize('delete', $jobDetail->job);

        if ($jobDetail->isStarted() || $jobDetail->job->isApproved()) {
            return back()->with('failedMessage', 'You can not delete a job that is started or approved.');
        }

        $jobDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
