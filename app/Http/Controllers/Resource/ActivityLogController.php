<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\DataTables\ActivityLogDatatable;

class ActivityLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Log Management');
    }

    public function index(ActivityLogDatatable $datatable)
    {
        abort_if(authUser()->cannot('Read Activity Log'), 403);

        $datatable->builder()->setTableId('activity-logs-datatable')->orderBy(1, 'asc');

        return $datatable->render('activity-logs.index');
    }
}