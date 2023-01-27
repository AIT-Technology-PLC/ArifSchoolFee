<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\SupplierDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;
use Illuminate\Support\Facades\DB;

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
        DB::transaction(function () use ($request) {
            $supplier = Supplier::firstOrCreate(
                $request->safe()->only(['company_name'] + ['company_id' => userCompany()->id]),
                $request->safe()->except(['company_name'] + ['company_id' => userCompany()->id]),
            );

            if ($request->hasFile('business_license_attachment')) {
                $supplier->update([
                    'business_license_attachment' => $request->business_license_attachment->store('supplier_business_licence', 'public'),
                ]);
            }
        });

        return redirect()->route('suppliers.index');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        DB::transaction(function () use ($supplier, $request) {
            $supplier->update($request->validated());

            if ($request->hasFile('business_license_attachment')) {
                $supplier->update([
                    'business_license_attachment' => $request->business_license_attachment->store('supplier_business_licence', 'public'),
                ]);
            }
        });

        return redirect()->route('suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
