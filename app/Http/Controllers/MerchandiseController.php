<?php

namespace App\Http\Controllers;

use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Purchase;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MerchandiseController extends Controller
{
    private $merchandise;

    public function __construct(Merchandise $merchandise)
    {
        $this->merchandise = $merchandise;
    }

    public function index()
    {
        //
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

    public function addToInventory(Purchase $purchase)
    {
        $data = $purchase->purchaseDetails
            ->map(function ($purchaseDetail) {
                return [
                    'product_id' => $purchaseDetail->product_id,
                    'total_received' => $purchaseDetail->quantity,
                    'total_on_hand' => $purchaseDetail->quantity,
                    'total_sold' => 0,
                    'total_broken' => 0,
                    'total_returns' => 0,
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'company_id' => auth()->user()->employee->company_id,
                    'created_at' => now()->toDateTimeString(),
                    'updated_at' => now()->toDateTimeString(),
                ];
            });

        $data = $data->toArray();

        DB::transaction(function () use ($data, $purchase) {
            $this->merchandise->insert($data);
            $purchase->changeStatusToAddedToInventory();
        });

        return redirect()->back();
    }

    public function show(Merchandise $merchandise)
    {
        //
    }

    public function edit(Merchandise $merchandise)
    {
        //
    }

    public function update(Request $request, Merchandise $merchandise)
    {
        //
    }

    public function destroy(Merchandise $merchandise)
    {
        //
    }
}
