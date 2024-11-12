<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\SubscriptionDatatable;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function index(SubscriptionDatatable $datatable)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Users'), 403);

        return $datatable->render('admin.subscriptions.index');
    }
}
