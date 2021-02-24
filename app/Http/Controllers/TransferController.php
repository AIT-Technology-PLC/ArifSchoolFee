<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Services\StoreSaleableProducts;
use App\Traits\Approvable;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TransferController extends Controller
{
    use PrependCompanyId, Approvable;

    private $transfer;

    public function __construct(transfer $transfer)
    {
        $this->authorizeResource(Transfer::class, 'transfer');

        $this->transfer = $transfer;
    }

    public function index(Transfer $transfer)
    {
        $transfers = $transfer->getAll()->load(['createdBy', 'updatedBy', 'approvedBy']);

        $totalTransfers = $transfer->countTransfersOfCompany();

        return view('transfers.index', compact('transfers', 'totalTransfers'));
    }

    public function create(Product $product, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentTransferCode = (Transfer::select('code')->companyTransfer()->latest()->first()->code) ?? 0;

        return view('transfers.create', compact('products', 'warehouses', 'currentTransferCode'));
    }

    public function store(Request $request)
    {
        $request['code'] = $this->prependCompanyId($request->code);

        $transferData = $request->validate([
            'code' => 'required|string|unique:transfers',
            'transfer' => 'required|array',
            'transfer.*.product_id' => 'required|integer',
            'transfer.*.warehouse_id' => 'required|integer',
            'transfer.*.to_warehouse_id' => 'required|integer|different:transfer.*.warehouse_id',
            'transfer.*.quantity' => 'required|numeric|min:1',
            'transfer.*.description' => 'nullable|string',
            'issued_on' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $transferData['status'] = 'Not Transferred';
        $transferData['company_id'] = auth()->user()->employee->company_id;
        $transferData['created_by'] = auth()->user()->id;
        $transferData['updated_by'] = auth()->user()->id;
        $transferData['approved_by'] = $this->approvedBy();

        $basicTransferData = Arr::except($transferData, 'transfer');
        $transferDetailsData = $transferData['transfer'];

        $transfer = DB::transaction(function () use ($basicTransferData, $transferDetailsData) {
            $transfer = $this->transfer->create($basicTransferData);
            $transfer->transferDetails()->createMany($transferDetailsData);
            $isTransferValid = StoreSaleableProducts::storeTransferredProducts($transfer);

            if (!$isTransferValid) {
                DB::rollback();
            }

            return $isTransferValid ? $transfer : false;
        });

        return $transfer ?
        redirect()->route('transfers.show', $transfer->id) :
        redirect()->back()->withInput($request->all());
    }

    public function transfer(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        DB::transaction(function () use ($transfer) {
            $transfer->changeStatusToTransferred();
            $isTransferValid = StoreSaleableProducts::storeTransferredProducts($transfer);

            if (!$isTransferValid) {
                DB::rollback();
            }
        });

        return redirect()->back();
    }

    public function show(Transfer $transfer)
    {
        $transfer->load(['transferDetails.product', 'transferDetails.warehouse', 'transferDetails.toWarehouse']);

        return view('transfers.show', compact('transfer'));
    }

    public function edit(Transfer $transfer, Product $product, Warehouse $warehouse)
    {
        $transfer->load(['transferDetails.product', 'transferDetails.warehouse', 'transferDetails.toWarehouse']);

        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('transfers.edit', compact('transfer', 'products', 'warehouses'));
    }

    public function update(Request $request, Transfer $transfer)
    {
        if ($transfer->isTransferDone()) {
            return redirect()->route('transfers.show', $transfer->id);
        }

        $request['code'] = $this->prependCompanyId($request->code);

        $transferData = $request->validate([
            'code' => 'required|string|unique:transfers,code,' . $transfer->id,
            'transfer' => 'required|array',
            'transfer.*.product_id' => 'required|integer',
            'transfer.*.warehouse_id' => 'required|integer',
            'transfer.*.to_warehouse_id' => 'required|integer|different:transfer.*.warehouse_id',
            'transfer.*.quantity' => 'required|numeric|min:1',
            'transfer.*.description' => 'nullable|string',
            'issued_on' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $transferData['updated_by'] = auth()->user()->id;

        $basicTransferData = Arr::except($transferData, 'transfer');
        $transferDetailsData = $transferData['transfer'];

        DB::transaction(function () use ($basicTransferData, $transferDetailsData, $transfer) {
            $transfer->update($basicTransferData);

            for ($i = 0; $i < count($transferDetailsData); $i++) {
                $transfer->transferDetails[$i]->update($transferDetailsData[$i]);
            }
        });

        return redirect()->route('transfers.show', $transfer->id);
    }

    public function destroy(Transfer $transfer)
    {
        $transfer->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function approve(Transfer $transfer)
    {
        $this->authorize('approve', $transfer);

        $message = 'This Transfer is already approved';

        if (!$transfer->isTransferApproved()) {
            $transfer->approveTransfer();
            $message = 'You have approved this Transfer successfully';
        }

        return redirect()->back()->with('successMessage', $message);
    }
}
