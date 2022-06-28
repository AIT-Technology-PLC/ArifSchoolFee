<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JobPlannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Job Management');
    }

    public function printed(Request $request)
    {
        $this->authorize('plan', Job::class);

        $reportData = collect(json_decode($request->planner, true));

        $reportData->transform(function ($data) {
            return collect($data);
        });

        return Pdf::loadView('job-planners.print', compact('reportData'))->stream();
    }
}