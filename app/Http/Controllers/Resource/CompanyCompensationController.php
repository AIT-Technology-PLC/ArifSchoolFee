<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CompanyCompensationDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompanyCompensationRequest;
use App\Http\Requests\UpdateCompanyCompensationRequest;
use App\Models\CompanyCompensation;
use Illuminate\Support\Facades\DB;

class CompanyCompensationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Company Compensation');

        $this->authorizeResource(CompanyCompensation::class);
    }

    public function index(CompanyCompensationDatatable $datatable)
    {
        $datatable->builder()->setTableId('company-compensations-datatable')->orderBy(1, 'desc');

        $totalCompensations = CompanyCompensation::count();

        return $datatable->render('company-compensations.index', compact('totalCompensations'));
    }

    public function create()
    {
        $compensations = CompanyCompensation::orderBy('name')->get(['id', 'name']);

        return view('company-compensations.create', compact('compensations'));
    }

    public function store(StoreCompanyCompensationRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->validated('compensation') as $compensation) {
                CompanyCompensation::create($compensation);
            }
        });

        return redirect()->route('company-compensations.index')->with('successMessage', 'New compensation are added.');
    }

    public function edit(CompanyCompensation $companyCompensation)
    {
        $compensations = CompanyCompensation::orderBy('name')->get(['id', 'name']);

        return view('company-compensations.edit', compact('companyCompensation', 'compensations'));
    }

    public function update(UpdateCompanyCompensationRequest $request, CompanyCompensation $companyCompensation)
    {
        $companyCompensation->update($request->validated());

        return redirect()->route('company-compensations.index');
    }

    public function destroy(CompanyCompensation $companyCompensation)
    {
        $companyCompensation->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}