<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Services\AddPurchasedItemsToInventory;
use App\Services\StoreSaleableProducts;
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
        $onHandMerchandises = $this->merchandise->getAllOnHandMerchandises();

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $outOfStockMerchandises = $product->getAllOutOfStockMerchandises();

        $totalOutOfStockMerchandises = $product->getTotalOutOfStockMerchandises($outOfStockMerchandises);

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises($this->merchandise->getCurrentMerchandiseLevelByProduct());

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($onHandMerchandises);

        $historyMerchandises = $this->merchandise->getMerchandisesInventoryHistory();

        return view('merchandises.index', compact('onHandMerchandises', 'totalDistinctOnHandMerchandises', 'outOfStockMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'historyMerchandises', 'totalWarehouseInUse'));
    }

    public function create(Product $product, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('merchandises.create', compact('products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => 'required|integer',
            'warehouse_id' => 'required|integer',
            'expires_on' => 'nullable|date',
            'description' => 'nullable|string|max:255',
            'total_received' => 'required|numeric',
        ]);

        $data['total_on_hand'] = $data['total_received'];
        $data['total_sold'] = 0;
        $data['total_broken'] = 0;
        $data['total_returns'] = 0;

        $data['created_by'] = auth()->user()->id;
        $data['updated_by'] = auth()->user()->id;
        $data['company_id'] = auth()->user()->employee->company_id;

        $this->merchandise->create($data);

        return redirect()->route('merchandises.index');
    }

    public function edit(Merchandise $merchandise, Warehouse $warehouse)
    {
        $merchandise->load(['warehouse', 'product']);

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('merchandises.edit', compact('merchandise', 'warehouses'));
    }

    public function update(Request $request, Merchandise $merchandise)
    {
        $data = $request->validate([
            'warehouse_id' => 'required|integer',
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

    public function addToInventory(Purchase $purchase)
    {
        DB::transaction(function () use ($purchase) {
            $purchase->changeStatusToAddedToInventory();
            AddPurchasedItemsToInventory::addToInventory($purchase);
        });

        return redirect()->back();
    }

    public function subtractFromInventory(Sale $sale)
    {
        DB::transaction(function () use ($sale) {
            $sale->changeStatusToSubtractedFromInventory();
            $isSaleValid = StoreSaleableProducts::storeSoldProducts($sale);

            if (!$isSaleValid) {
                DB::rollback();
            }
        });

        return redirect()->back();
    }

    public function showCurrentInventoryLevelByProducts(Product $product, Warehouse $warehouse)
    {
        $onHandMerchandises = $this->merchandise->getCurrentMerchandiseLevelByProduct();

        $totalDistinctOnHandMerchandises = $this->merchandise->getTotalDistinctOnHandMerchandises($onHandMerchandises);

        $outOfStockMerchandises = $product->getAllOutOfStockMerchandises();

        $totalOutOfStockMerchandises = $product->getTotalOutOfStockMerchandises($outOfStockMerchandises);

        $totalDistinctLimitedMerchandises = $this->merchandise->getTotalDistinctLimitedMerchandises($onHandMerchandises);

        $totalWarehouseInUse = $warehouse->getTotalWarehousesUsed($this->merchandise->getAllOnHandMerchandises());

        return view('merchandises.levels.index', compact('onHandMerchandises', 'outOfStockMerchandises', 'totalDistinctOnHandMerchandises', 'totalOutOfStockMerchandises', 'totalDistinctLimitedMerchandises', 'totalWarehouseInUse'));
    }

    public function show(Merchandise $merchandise)
    {
        //
    }

    public function destroy(Merchandise $merchandise)
    {
        //
    }
}
