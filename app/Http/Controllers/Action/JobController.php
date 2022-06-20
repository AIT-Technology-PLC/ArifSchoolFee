<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateJobAvailableRequest;
use App\Http\Requests\UpdateJobWipRequest;
use App\Models\Job;
use App\Services\Models\JobService;

class JobController extends Controller
{
    private $jobService;

    public function __construct(JobService $jobService)
    {
        $this->middleware('isFeatureAccessible:Job Management');

        $this->jobService = $jobService;
    }

    public function approve(Job $job, ApproveTransactionAction $action)
    {
        $this->authorize('approve', $job);

        [$isExecuted, $message] = $action->execute($job, JobApproved::class);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function addToWorkInProcess(UpdateJobWipRequest $request, Job $job)
    {
        $this->authorize('update', Job::class);

        [$isExecuted, $message] = $this->jobService->addToWorkInProcess($request, $job);

        // [$isExecuted, $message] = $action->execute($job, JobProgress::class); call JobProgress notifcation.

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function addToAvailable(UpdateJobAvailableRequest $request, Job $job)
    {
        $this->authorize('update', Job::class);

        [$isExecuted, $message] = $this->jobService->addToAvailable($request, $job);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }
}