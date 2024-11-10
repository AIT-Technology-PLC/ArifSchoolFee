<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\DataTables\UserLogDatatable;
use App\Models\UserLog;

class UserLogController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Log Management');

        $this->authorizeResource(UserLog::class, 'userLog');
    }
    
    public function index(UserLogDatatable $datatable)
    {
        $datatable->builder()->setTableId('user-logs-datatable')->orderBy(1, 'asc');

        return $datatable->render('user-logs.index');
    }
}
