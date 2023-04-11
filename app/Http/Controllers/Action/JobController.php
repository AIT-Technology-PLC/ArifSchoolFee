<?php

namespace App\Http\Controllers\Action;

use App\Actions\ApproveTransactionAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateJobAvailableRequest;
use App\Http\Requests\UpdateJobWipRequest;
use App\Models\Job;
use App\Notifications\JobProgress;
use App\Services\Models\JobService;
use App\Utilities\Notifiables;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;

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

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back()->with('successMessage', $message);
    }

    public function addToWorkInProcess(UpdateJobWipRequest $request, Job $job)
    {
        $this->authorize('addToWip', Job::class);

        [$isExecuted, $message] = $this->jobService->addToWorkInProcess($request->validated('job'), $job, auth()->user());

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        Notification::send(Notifiables::byPermissionAndWarehouse('Read Job', $job->factory_id, $job->createdBy), new JobProgress($job));

        return back()->with('successMessage', $message);
    }

    public function addToAvailable(UpdateJobAvailableRequest $request, Job $job)
    {
        $this->authorize('addToAvailable', Job::class);

        [$isExecuted, $message] = $this->jobService->addToAvailable($request->validated('job'), $job, auth()->user());

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }

    public function close(Job $job)
    {
        $this->authorize('close', $job);

        [$isExecuted, $message] = $this->jobService->close($job);

        if (! $isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return back();
    }

    public function convertToSale(Request $request, Job $job)
    {
        $this->authorize('create', Sale::class);

        [$isExecuted, $message, $data] = $this->jobService->convertToSale($job);

        if (!$isExecuted) {
            return back()->with('failedMessage', $message);
        }

        return redirect()->route('sales.create')->withInput($request->merge($data)->all());
    }
}