<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\Admin\CompanyDatatable;
use App\Http\Controllers\Controller;
use App\Models\Company;

class CompanyController extends Controller
{
    public function index(CompanyDatatable $datatable)
    {
        abort_if(!authUser()->isAdmin(), 403);

        $enabledCompanies = Company::enabled()->count();

        $disabledCompanies = Company::disabled()->count();

        $companies = $enabledCompanies + $disabledCompanies;

        return $datatable->render('admin.companies.index', compact('enabledCompanies', 'disabledCompanies', 'companies'));
    }

    public function create()
    {
        abort_if(!authUser()->isAdmin(), 403);

        return view('admin.companies.create');
    }

    public function show(Company $company)
    {
        abort_if(!authUser()->isAdmin(), 403);

        return view('admin.companies.show', compact('company'));
    }
}
