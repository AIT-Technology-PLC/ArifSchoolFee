<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\PadDatatable;
use App\DataTables\PadFieldDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StorePadRequest;
use App\Models\Pad;
use App\Services\Models\PadService;
use Illuminate\Http\Request;

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
        return view('pads.create');
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
        $pad->load(['padFields']);

        return view('pads.edit', compact('pad'));
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Pad $pad)
    {
        abort_if($pad->isEnabled(), 403);

        $pad->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
