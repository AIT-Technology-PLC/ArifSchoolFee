<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Services\StoreSaleableProducts;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    use PrependCompanyId;

    private $transfer;

    public function __construct(transfer $transfer)
    {
        $this->transfer = $transfer;
    }

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
        $request['code'] = $this->prependCompanyId($request->code);

        $transferData = $request->validate([
            'code' => 'required|string|unique:transfers',
            'transfer' => 'required|array',
            'transfer.*.product_id' => 'required|integer',
            'transfer.*.warehouse_id' => 'required|integer',
            'transfer.*.quantity' => 'required|numeric|min:1',
            'transfer.*.desciption' => 'nullable|string',
            'issued_on' => 'required|date',
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $transferData['company_id'] = auth()->user()->employee->company_id;
        $transferData['created_by'] = auth()->user()->id;
        $transferData['updated_by'] = auth()->user()->id;

        $basicTransferData = Arr::except($transferData, 'transfer');
        $transferDetailsData = $transferData['transfer'];

        $transfer = DB::transaction(function () use ($basicTransferData, $transferDetailsData) {
            $transfer = $this->transfer->create($basicTransferData);
            $transfer->transferDetails()->createMany($transferDetailsData);
            $isTransferValid = false;

            if ($transfer->isTransferDone()) {
                $isTransferValid = StoreSaleableProducts::areProductsAvailableOnHand($transfer->transferDetails);
            }

            if (!$isTransferValid) {
                DB::rollback();
            }

            return $isTransferValid ? $transfer : false;
        });

        return $transfer ?
        redirect()->route('transfers.show', $transfer->id) :
        redirect()->back()->withInput($request->all());
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
