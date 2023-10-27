<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\JobDatatable;
use App\DataTables\JobDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreJobRequest;
use App\Http\Requests\UpdateJobRequest;
use App\Models\Job;
use App\Notifications\JobCreated;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class JobController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Job Management');

        $this->authorizeResource(Job::class);
    }

    public function index(JobDatatable $datatable)
    {
        $datatable->builder()->setTableId('jobs-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalJobs = Job::count();

        $totalApproved = Job::approved()->count();

        $totalNotApproved = Job::notApproved()->count();

        return $datatable->render('jobs.index', compact('totalJobs', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $warehouses = auth()->user()->getAllowedWarehouses('sales');

        $currentJobCode = nextReferenceNumber('job_orders');

        return view('jobs.create', compact('warehouses', 'currentJobCode'));
    }

    public function store(StoreJobRequest $request)
    {
        $job = DB::transaction(function () use ($request) {
            $job = Job::create($request->safe()->except('job'));

            $job->jobDetails()->createMany($request->validated('job'));

            $job->createCustomFields($request->validated('customField'));

            Notification::send(Notifiables::byNextActionPermission('Approve Job'), new JobCreated($job));

            return $job;
        });

        return redirect()->route('jobs.show', $job->id);
    }

    public function show(Job $job, JobDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('job-details-datatable');

        $job->load(['jobDetails', 'jobExtras.product', 'jobExtras.executedBy', 'customFieldValues.customField']);

        return $datatable->render('jobs.show', compact('job'));
    }

    public function edit(Job $job)
    {
        $warehouses = auth()->user()->getAllowedWarehouses('sales');

        if ($job->isStarted() || $job->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a job that is started or approved.');
        }

        $job->load(['jobDetails']);

        return view('jobs.edit', compact('job', 'warehouses'));
    }

    public function update(UpdateJobRequest $request, Job $job)
    {
        if ($job->isStarted() || $job->isApproved()) {
            return back()->with('failedMessage', 'You can not modify a job that is started or approved.');
        }

        DB::transaction(function () use ($request, $job) {
            $job->update($request->safe()->except('job'));

            $job->jobDetails()->forceDelete();

            $job->jobDetails()->createMany($request->validated('job'));

            $job->createCustomFields($request->validated('customField'));
        });

        return redirect()->route('jobs.show', $job->id);
    }

    public function destroy(Job $job)
    {
        if ($job->isStarted() || $job->isApproved()) {
            return back()->with('failedMessage', 'You can not delete a job that is started or approved.');
        }

        $job->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
