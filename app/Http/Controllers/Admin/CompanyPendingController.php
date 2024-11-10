<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\DataTables\Admin\PendingCompanyDatatable;

class CompanyPendingController extends Controller
{
    public function pending (PendingCompanyDatatable $datatable)
    {
        abort_if(authUser()->cannot('Manage Admin Panel Companies'), 403);

        $datatable->builder()->setTableId('pending-companies-datatable')->orderBy(1, 'asc');

        $totalPending = Company::disabled()->count();

        return $datatable->render('admin.schools.pending', compact('totalPending'));
    }
}
