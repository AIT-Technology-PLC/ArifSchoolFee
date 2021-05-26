<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateMerchandiseRequest;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Support\Facades\DB;

class MerchandiseController extends Controller
{
    private $merchandise;

    public function __construct(Merchandise $merchandise)
    {
        $this->authorizeResource(Merchandise::class, 'merchandise');

        $this->merchandise = $merchandise;
    }

    public function index(Merchandise $merchandise, Product $product, Warehouse $warehouse)
    {
        $onHandMerchandises = $merchandise->getAll()->load('product.productCategory');

        $onHandMerchandiseProducts = $onHandMerchandises->pluck('product')->unique();

        $totalDistinctOnHandMerchandises = $onHandMerchandiseProducts->count();

        $totalDistinctLimitedMerchandises = $merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);
        
        $outOfStockMerchandiseProducts = $product->getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)->load('productCategory');

        $totalOutOfStockMerchandises = $outOfStockMerchandiseProducts->count();

        $warehouses = $warehouse->getAllWithoutRelations();

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($onHandMerchandises);

        return view('merchandises.index', compact('merchandise', 'onHandMerchandises', 'onHandMerchandiseProducts', 'outOfStockMerchandiseProducts', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'totalWarehouseInUse', 'warehouses'));
    }

    public function edit(Merchandise $merchandise, Warehouse $warehouse)
    {
        $merchandise->load(['product']);

        return view('merchandises.edit', compact('merchandise'));
    }

    public function update(UpdateMerchandiseRequest $request, Merchandise $merchandise)
    {
        DB::transaction(function () use ($request, $merchandise) {
            $merchandise->update($request->all());
            $merchandise->decrementTotalOnHandQuantity();
        });

        return redirect()->route('merchandises.index');
    }

    public function destroy(Merchandise $merchandise)
    {
        $merchandise->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
