<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\CompensationDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreCompensationRequest;
use App\Http\Requests\UpdateCompensationRequest;
use App\Models\Compensation;

class CompensationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Compensation Management');

        $this->authorizeResource(Compensation::class);
    }

    public function index(CompensationDatatable $datatable)
    {
        $datatable->builder()->setTableId('compensations-datatable')->orderBy(1, 'desc');

        $totalCompensations = Compensation::count();

        return $datatable->render('compensations.index', compact('totalCompensations'));
    }

    public function create()
    {
        $compensations = Compensation::orderBy('name')->get(['id', 'name']);

        return view('compensations.create', compact('compensations'));
    }

    public function store(StoreCompensationRequest $request)
    {
        Compensation::firstOrCreate(
            $request->only(['name'] + ['company_id' => userCompany()->id]),
            $request->except(['name'] + ['company_id' => userCompany()->id])
        );

        return redirect()->route('compensations.index')->with('successMessage', 'New compensation are added.');
    }

    public function edit(Compensation $compensation)
    {
        $compensationNames = Compensation::orderBy('name')->get(['id', 'name']);

        return view('compensations.edit', compact('compensation', 'compensationNames'));
    }

    public function update(UpdateCompensationRequest $request, Compensation $compensation)
    {
        $compensation->update($request->validated());

        return redirect()->route('compensations.index');
    }
}