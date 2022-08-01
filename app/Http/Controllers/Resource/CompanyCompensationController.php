<?php

namespace App\Http\Controllers\Resource;

use Illuminate\Support\Facades\DB;
use App\Models\CompanyCompensation;
use App\Http\Controllers\Controller;
use App\DataTables\CompanyCompensationDataTable;
use App\Http\Requests\StoreCompanyCompensationRequest;
use App\Http\Requests\UpdateCompanyCompensationRequest;

class CompanyCompensationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Company Compensation');

        $this->authorizeResource(CompanyCompensation::class);
    }

    public function index(CompanyCompensationDataTable $datatable)
    {
        $datatable->builder()->setTableId('company_compensations-datatable')->orderBy(1, 'desc');

        $totalCompensations = CompanyCompensation::count();

        return $datatable->render('company_compensations.index', compact('totalCompensations'));
    }

    public function create()
    {
        return view('company_compensations.create');
    }

    public function store(StoreCompanyCompensationRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('compensation') as $compensation) {
                CompanyCompensation::create($compensation);
            }
        });

        return redirect()->route('company_compensations.index')->with('successMessage', 'New compensation are added.');
    }

    public function edit(CompanyCompensation $companyCompensation)
    {
        return view('company_compensations.edit', compact('companyCompensation'));
    }

    public function update(UpdateCompanyCompensationRequest $request, CompanyCompensation $companyCompensation)
    {
        $companyCompensation->update($request->validated());

        return redirect()->route('company_compensations.index');
    }

    public function destroy(CompanyCompensation $companyCompensation)
    {
        $companyCompensation->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}