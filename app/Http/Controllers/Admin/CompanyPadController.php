<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\PadFieldDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePadRequest;
use App\Http\Requests\UpdatePadRequest;
use App\Models\Company;
use App\Models\Pad;
use App\Services\Models\PadService;
use Barryvdh\DomPDF\Facade\Pdf;

class CompanyPadController extends Controller
{
    private $padService;

    public function __construct(PadService $padService)
    {
        $this->padService = $padService;
    }

    public function create(Company $company)
    {
        $features = (new Pad)->converts();

        return view('admin.pads.create', compact('features', 'company'));
    }

    public function store(StorePadRequest $request, Company $company)
    {
        $pad = $this->padService->store($request->validated(), $company);

        return redirect()->route('admin.pads.show', $pad->id);
    }

    public function show(Pad $pad, PadFieldDatatable $datatable)
    {
        $datatable->builder()->setTableId('pad-fields-datatable');

        $pad->load(['padPermissions', 'padStatuses']);

        return $datatable->render('admin.pads.show', compact('pad'));
    }

    public function edit(Pad $pad)
    {
        $features = (new Pad)->converts();

        $pricesFields = $this->padService->generatePriceFields()->pluck('label');

        $excludedPadFields = $this->padService->generatePaymentTermFields()->pluck('label')->merge($pricesFields);

        $pad->load(['padStatuses', 'padFields' => function ($query) use ($excludedPadFields) {
            $query->with('padRelation')->whereNotIn('label', $excludedPadFields);
        }]);

        return view('admin.pads.edit', compact('pad', 'features'));
    }

    public function update(UpdatePadRequest $request, Pad $pad)
    {
        $pad = $this->padService->update($pad, $request->validated());

        return redirect()->route('admin.pads.show', $pad->id);
    }

    public function destroy(Pad $pad)
    {
        abort_if($pad->isEnabled(), 403);

        $pad->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
