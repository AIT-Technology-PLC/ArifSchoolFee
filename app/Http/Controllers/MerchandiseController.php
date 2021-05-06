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

    public function index(Product $product, Warehouse $warehouse)
    {
        $onHandMerchandises = $this->merchandise->getAllOnHandMerchandises()->load(['product', 'warehouse', 'createdBy', 'updatedBy']);

        $onHandMerchandiseProducts = $onHandMerchandises->pluck('product')->unique();

        $historyMerchandises = $this->merchandise->getMerchandisesInventoryHistory()->load(['product', 'warehouse', 'createdBy', 'updatedBy']);

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises(
            $this->merchandise->getMerchandiseProductsLevel()->load('product')
        );

        $totalOutOfStockMerchandises = $product->getOutOfStockMerchandiseProducts($onHandMerchandiseProducts)->count();

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($onHandMerchandises);

        return view('merchandises.index', compact('onHandMerchandises', 'historyMerchandises', 'totalDistinctOnHandMerchandises', 'totalDistinctLimitedMerchandises', 'totalOutOfStockMerchandises', 'totalWarehouseInUse'));
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
