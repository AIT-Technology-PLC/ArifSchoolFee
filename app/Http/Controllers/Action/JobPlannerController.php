<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class JobPlannerController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Job Planner');
    }

    public function printed(Request $request)
    {
        $reportData = collect(json_decode($request->planner, true));

        $reportData->transform(function ($data) {
            return collect($data);
        });

        return Pdf::loadView('job-planner.print', compact('reportData'))->stream();
    }
}