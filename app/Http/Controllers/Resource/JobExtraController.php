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
        $job = DB::transaction(function () use ($request, $job) {
            $job->jobExtras()->createMany($request->safe()['job']);
        });

        return back();
    }

    public function edit(JobExtra $jobExtra)
    {
        return view('job-extras.edit', compact('jobExtra'));
    }

    public function update(UpdateJobExtraRequest $request, JobExtra $jobExtra)
    {
        if ($jobExtra->isAdded() || $jobExtra->isSubtracted()) {
            return redirect()->route('jobs.show')->with('jobExtraModified', 'Job extra that is subtracted/added can not be modified.');
        }

        $jobExtra->update($request->safe()->except(''));

        return redirect()->route('jobs.show', $jobExtra->job_id);
    }

    public function destroy(JobExtra $jobExtra)
    {
        $jobExtra->forceDelete();

        return back()->with('jobExtraDeleted', 'Deleted successfully.');
    }
}