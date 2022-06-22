<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Models\JobExtra;
use App\Services\Models\JobService;

class JobExtraController extends Controller
{
    private $jobService;

    public function __construct(JobService $jobService)
    {
        $this->middleware('isFeatureAccessible:Job Management');

        $this->jobService = $jobService;
    }

    public function addExtra(JobExtra $jobExtra)
    {
        $this->authorize('addExtra', $jobExtra->job);

        if ($jobExtra->job->jobCompletionRate == 100) {
            return false;
        }

        [$isExecuted, $message] = $this->jobService->addExtra($jobExtra, auth()->user());

        if (!$isExecuted) {
            return back()->with('jobExtrafailedMessage', $message);
        }

        return back();
    }

    public function subtractExtra(JobExtra $jobExtra)
    {
        $this->authorize('subtractExtra', $jobExtra->job);

        [$isExecuted, $message] = $this->jobService->subtractExtra($jobExtra, auth()->user());

        if (!$isExecuted) {
            return back()->with('jobExtrafailedMessage', $message);
        }

        return back();
    }
}
