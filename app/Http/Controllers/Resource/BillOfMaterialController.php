<?php

namespace App\Http\Controllers;

use App\DataTables\BillOfMaterialDatatable;
use App\DataTables\BillOfMaterialDetailDatatable;
use App\Http\Requests\StoreBillOfMaterialRequest;
use App\Http\Requests\UpdateBillOfMaterialRequest;
use App\Models\BillOfMaterial;
use Illuminate\Support\Facades\DB;

class BillOfMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Bill Of Material Management');

        $this->authorizeResource(BillOfMaterial::class, 'billOfMaterial');
    }

    public function index(BillOfMaterialDatatable $datatable)
    {
        $datatable->builder()->setTableId('bill-of-materials-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalBillOfMaterials = BillOfMaterial::count();

        return $datatable->render('bill-of-materials.index', compact('totalBillOfMaterials'));
    }

    public function create()
    {
        return view('bill-of-materials.create');
    }

    public function store(StoreBillOfMaterialRequest $request)
    {
        $billOfMaterial = DB::transaction(function () use ($request) {
            $billOfMaterial = BillOfMaterial::create($request->safe()->except('billOfMaterial'));

            $billOfMaterial->billOfMaterialDetails()->createMany($request->billOfMaterial);

            return $billOfMaterial;
        });

        return redirect()->route('bill-of-materials.show', $billOfMaterial->id);
    }

    public function show(BillOfMaterial $billOfMaterial, BillOfMaterialDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('bill-of-material-details-datatable');

        $billOfMaterial->load(['billOfMaterialDetails.product']);

        return $datatable->render('bill-of-materials.show', compact('billOfMaterial'));
    }

    public function edit(BillOfMaterial $billOfMaterial)
    {
        $billOfMaterial->load(['billOfMaterialDetails.product']);

        return view('bill-of-materials.edit', compact('billOfMaterial'));
    }

    public function update(UpdateBillOfMaterialRequest $request, BillOfMaterial $billOfMaterial)
    {
        DB::transaction(function () use ($request, $billOfMaterial) {
            $billOfMaterial->update($request->safe()->except('billOfMaterial'));

            for ($i = 0; $i < count($request->billOfMaterial); $i++) {
                $billOfMaterial->billOfMaterialDetails[$i]->update($request->billOfMaterial[$i]);
            }
        });

        return redirect()->route('bill-of-materials.show', $billOfMaterial->id);
    }

    public function destroy(BillOfMaterial $billOfMaterial)
    {
        abort_if(!auth()->user()->can('Delete BOM'), 403);

        $billOfMaterial->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}