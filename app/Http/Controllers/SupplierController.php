<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Http\Requests\UpdateSupplierRequest;
use App\Models\Supplier;

class SupplierController extends Controller
{
    private $supplier;

    public function __construct(Supplier $supplier)
    {
        $this->middleware('isFeatureAccessible:Supplier Management');

        $this->authorizeResource(Supplier::class, 'supplier');

        $this->supplier = $supplier;
    }

    public function index()
    {
        $suppliers = $this->supplier->getAll();

        $totalSuppliers = $this->supplier->countSuppliersOfCompany();

        return view('suppliers.index', compact('suppliers', 'totalSuppliers'));
    }

    public function create()
    {
        return view('suppliers.create');
    }

    public function store(StoreSupplierRequest $request)
    {
        $this->supplier->firstOrCreate(
            $request->only(['company_name']),
            $request->except(['company_name']),
        );

        return redirect()->route('suppliers.index');
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(UpdateSupplierRequest $request, Supplier $supplier)
    {
        $supplier->update($request->all());

        return redirect()->route('suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
