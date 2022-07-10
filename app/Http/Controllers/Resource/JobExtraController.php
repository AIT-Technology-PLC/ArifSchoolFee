<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobExtraRequest;
use App\Http\Requests\UpdateJobExtraRequest;
use App\Models\Job;
use App\Models\JobExtra;
use Illuminate\Support\Facades\DB;

class JobExtraController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Job Management');
    }

    public function store(StoreJobExtraRequest $request, Job $job)
    {
        $this->authorize('create', $job);

        if (! $job->isApproved()) {
            return back()->with('jobExtraFailedMessage', 'This job is not approved yet.');
        }

        $job = DB::transaction(function () use ($request, $job) {
            $job->jobExtras()->createMany($request->validated('jobExtra'));
        });

        return back();
    }

    public function edit(JobExtra $jobExtra)
    {
        $this->authorize('update', $jobExtra->job);

        if ($jobExtra->isAdded() || $jobExtra->isSubtracted()) {
            return back()->with('jobExtraFailedMessage', 'Job extra that is subtracted/added can not be modified.');
        }

        return view('job-extras.edit', compact('jobExtra'));
    }

    public function update(UpdateJobExtraRequest $request, JobExtra $jobExtra)
    {
        $this->authorize('update', $jobExtra->job);

        if ($jobExtra->isAdded() || $jobExtra->isSubtracted()) {
            return redirect()->route('jobs.show')->with('jobExtraFailedMessage', 'Job extra that is subtracted/added can not be modified.');
        }

        $jobExtra->update($request->validated());

        return redirect()->route('jobs.show', $jobExtra->job_order_id);
    }

    public function destroy(JobExtra $jobExtra)
    {
        $this->authorize('delete', $jobExtra->job);

        if ($jobExtra->isAdded() || $jobExtra->isSubtracted()) {
            return back()->with('jobExtraFailedMessage', 'Job extra that is subtracted/added can not be deleted.');
        }

        $jobExtra->forceDelete();

        return back()->with('jobExtraSuccessMessage', 'Deleted successfully.');
    }
}
