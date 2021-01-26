<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transfer;
use App\Models\Warehouse;
use Illuminate\Http\Request;

class TransferController extends Controller
{
    public function index()
    {
        //
    }
    
    public function create(Product $product, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('transfers.create', compact('products', 'warehouses'));
    }
    
    public function store(Request $request)
    {
        //
    }
    
    public function show(Transfer $transfer)
    {
        //
    }
    
    public function edit(Transfer $transfer)
    {
        //
    }
    
    public function update(Request $request, Transfer $transfer)
    {
        //
    }
    
    public function destroy(Transfer $transfer)
    {
        //
    }
}
