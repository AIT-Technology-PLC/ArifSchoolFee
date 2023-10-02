<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\MerchandiseBatchDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateMerchandiseBatchRequest;
use App\Models\MerchandiseBatch;

class MerchandiseBatchController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Merchandise Inventory');

        $this->authorizeResource(MerchandiseBatch::class);
    }

    public function index(MerchandiseBatchDatatable $datatable)
    {
        $datatable->builder()->setTableId('merchandise-batches-datatable')->orderBy(1, 'asc');

        $batches = MerchandiseBatch::count();

        return $datatable->render('batches.index', compact('batches'));
    }

    public function edit(MerchandiseBatch $merchandiseBatch)
    {
        return view('batches.edit', compact('merchandiseBatch'));
    }

    public function update(UpdateMerchandiseBatchRequest $request, MerchandiseBatch $merchandiseBatch)
    {
        $merchandiseBatch->update($request->validated());

        return redirect()->route('merchandise-batches.index');
    }
}
