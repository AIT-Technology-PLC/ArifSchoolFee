<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\PadDatatable;
use App\DataTables\PadFieldDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePadRequest;
use App\Http\Requests\UpdatePadRequest;
use App\Models\Pad;
use App\Services\Models\PadService;

class PadController extends Controller
{
    private $padService;

    public function __construct(PadService $padService)
    {
        $this->middleware('isFeatureAccessible:Pad Management');

        $this->authorizeResource(Pad::class, 'pad');

        $this->padService = $padService;
    }

    public function index(Pad $pad, PadDatatable $datatable)
    {
        $datatable->builder()->setTableId('pads-datatable');

        $totalPads = Pad::count();

        $totalEnabledPads = Pad::enabled()->count();

        $totalDisabledPads = Pad::disabled()->count();

        return $datatable->render('pads.index', compact('totalPads', 'totalEnabledPads', 'totalDisabledPads'));
    }

    public function create()
    {
        $features = (new Pad)->converts();

        return view('pads.create', compact('features'));
    }

    public function store(StorePadRequest $request)
    {
        $pad = $this->padService->store($request->validated());

        return redirect()->route('pads.show', $pad->id);
    }

    public function show(Pad $pad, PadFieldDatatable $datatable)
    {
        $datatable->builder()->setTableId('pad-fields-datatable');

        $pad->load(['padPermissions']);

        return $datatable->render('pads.show', compact('pad'));
    }

    public function edit(Pad $pad)
    {
        $features = (new Pad)->converts();
        
        $pricesFields = $this->padService->generatePriceFields()->pluck('label');
        
        $excludedPadFields = $this->padService->generatePaymentTermFields()->pluck('label')->merge($pricesFields);

        $pad->load(['padFields' => function ($query) use ($excludedPadFields) {
            $query->with('padRelation')->whereNotIn('label', $excludedPadFields);
        }]);

        return view('pads.edit', compact('pad', 'features'));
    }

    public function update(UpdatePadRequest $request, Pad $pad)
    {
        $pad = $this->padService->update($pad, $request->validated());

        return redirect()->route('pads.show', $pad->id);
    }

    public function destroy(Pad $pad)
    {
        abort_if($pad->isEnabled(), 403);

        $pad->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
