<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Siv;
use App\Models\Warehouse;
use App\Traits\ApproveInventory;
use Illuminate\Http\Request;

class SivController extends Controller
{
    use ApproveInventory;

    private $siv, $permission;

    public function __construct(Siv $siv)
    {
        // $this->authorizeResource(Siv::class, 'siv');

        $this->siv = $siv;

        $this->permission = 'Execute SIV';
    }

    public function index()
    {
        return view('sivs.index');
    }

    public function create(Product $product, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentSivCode = (Siv::select('code')->companySiv()->latest()->first()->code) ?? 0;

        return view('sivs.create', compact('products', 'warehouses', 'currentSivCode'));
    }

    public function store(Request $request)
    {
        //
    }

    public function show(Siv $siv)
    {
        return view('sivs.show');
    }

    public function edit(Siv $siv)
    {
        return view('sivs.edit');
    }

    public function update(Request $request, Siv $siv)
    {
        //
    }

    public function destroy(Siv $siv)
    {
        //
    }
}
