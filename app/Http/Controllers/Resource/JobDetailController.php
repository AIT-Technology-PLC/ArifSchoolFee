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

        $jobDetail->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
