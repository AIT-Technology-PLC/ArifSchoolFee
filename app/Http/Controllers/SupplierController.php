<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class SupplierController extends Controller
{
    private $supplier;

    public function __construct(Supplier $supplier)
    {
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

    public function store(Request $request)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'tin' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $data['company_id'] = auth()->user()->employee->company_id;
        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;

        $this->supplier->firstOrCreate(
            Arr::only($data, ['company_name', 'company_id']),
            Arr::except($data, ['company_name', 'company_id'])
        );

        return redirect()->route('suppliers.index');
    }

    public function show(Supplier $supplier)
    {
        //
    }

    public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier)
    {
        $data = $request->validate([
            'company_name' => 'required|string|max:255',
            'tin' => 'nullable|numeric',
            'address' => 'nullable|string|max:255',
            'contact_name' => 'nullable|string|max:255',
            'email' => 'nullable|string|email|max:255',
            'phone' => 'nullable|string|max:255',
            'country' => 'nullable|string|max:255',
        ]);

        $data['updated_by'] = auth()->user()->id;

        $supplier->update($data);

        return redirect()->route('suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
