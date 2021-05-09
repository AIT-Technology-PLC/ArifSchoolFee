<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupplierRequest;
use App\Models\Supplier;
use Illuminate\Http\Request;

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

    public function store(StoreSupplierRequest $request)
    {
        $this->supplier->firstOrCreate(
            $request->only(['company_name', 'company_id']),
            $request->except(['company_name', 'company_id']),
        );

        return redirect()->route('suppliers.index');
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

        $data['updated_by'] = auth()->id();

        $supplier->update($data);

        return redirect()->route('suppliers.index');
    }

    public function destroy(Supplier $supplier)
    {
        $supplier->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
