<?php

namespace App\Http\Controllers;

use App\DataTables\BillOfMaterialDatatable;
use App\Models\BillOfMaterial;
use Illuminate\Http\Request;

class BillOfMaterialController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Bill Of Material Management');
    }

    public function index(BillOfMaterialDatatable $datatable)
    {
        $datatable->builder()->setTableId('bill-of-materials-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalBillOfMaterials = BillOfMaterial::count();

        return $datatable->render('bill-of-materials.index', compact('totalBillOfMaterials'));
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        //
    }
}