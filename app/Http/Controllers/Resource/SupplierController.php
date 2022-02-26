<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\SupplierDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Supplier Management');

        $this->authorizeResource(Supplier::class, 'supplier');
    }

    public function index(SupplierDatatable $datatable)
    {
        $datatable->builder()->setTableId('suppliers-datatable')->orderBy(1, 'asc');

        $suppliers = Supplier::with(['createdBy', 'updatedBy'])->orderBy('company_name')->get();

        $totalSuppliers = Supplier::count();

        return $datatable->render('suppliers.index', compact('totalSuppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        Supplier::firstOrCreate(
            $request->only(['company_name'] + ['company_id' => userCompany()->id]),
            $request->except(['company_name'] + ['company_id' => userCompany()->id]),
        );

        return redirect()->route('suppliers.index');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->validated());

        return redirect()->route('suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
