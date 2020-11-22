<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    private $supplier;

    function __construct(Supplier $supplier)
    {
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
        //
    }

    public function show(Supplier $supplier)
    {
        //
    }

    public function edit(Supplier $supplier)
    {
        //
    }

    public function update(Request $request, Supplier $supplier)
    {
        //
    }

    public function destroy(Supplier $supplier)
    {
        //
    }
}
