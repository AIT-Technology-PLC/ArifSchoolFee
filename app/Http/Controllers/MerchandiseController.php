<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;
use Illuminate\Http\Request;
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

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $outOfStockMerchandises = $product->getAllOutOfStockMerchandises();

        $totalOutOfStockMerchandises = $product->getTotalOutOfStockMerchandises($outOfStockMerchandises);

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises($this->merchandise->getCurrentMerchandiseLevelByProduct()->load('product'));

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($onHandMerchandises);

        $historyMerchandises = $this->merchandise->getMerchandisesInventoryHistory()->load(['product', 'warehouse', 'createdBy', 'updatedBy']);

        return view('merchandises.index', compact('onHandMerchandises', 'totalDistinctOnHandMerchandises', 'outOfStockMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'historyMerchandises', 'totalWarehouseInUse'));
    }

    public function edit(Merchandise $merchandise, Warehouse $warehouse)
    {
        $merchandise->load(['product']);

        return view('merchandises.edit', compact('merchandise'));
    }

    public function update(Request $request, Merchandise $merchandise)
    {
        $data = $request->validate([
            'total_returns' => 'nullable|numeric',
            'total_broken' => 'nullable|numeric',
        ]);

        $data["total_returns"] = $merchandise->isReturnedQuantityValueValid($data['total_returns']);
        $data["total_broken"] = $merchandise->isBrokenQuantityValueValid($data['total_broken']);

        DB::transaction(function () use ($merchandise, $data) {
            $merchandise->update($data);
            $merchandise->decrementTotalOnHandQuantity();
        });

        return redirect()->route('merchandises.index');
    }

    public function show(Merchandise $merchandise)
    {
        //
    }

    public function destroy(Merchandise $merchandise)
    {
        $merchandise->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
