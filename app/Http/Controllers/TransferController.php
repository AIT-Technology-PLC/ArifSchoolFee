<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferRequest;
use App\Models\Product;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Notifications\TransferMade;
use App\Notifications\TransferPrepared;
use App\Services\InventoryOperationService;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TransferController extends Controller
{
    use NotifiableUsers, ApproveInventory;

    private $transfer;

    private $permission;

    public function __construct(transfer $transfer)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Transfer');

        $this->authorizeResource(Transfer::class, 'transfer');

        $this->transfer = $transfer;

        $this->permission = 'Make Transfer';
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

    public function store(StoreTransferRequest $request)
    {
        $transfer = DB::transaction(function () use ($request) {
            $transfer = $this->transfer->create($request->except('transfer'));

            $transfer->transferDetails()->createMany($request->transfer);

            Notification::send($this->notifiableUsers('Approve Transfer'), new TransferPrepared($transfer));

            return $transfer;
        });

        return redirect()->route('transfers.show', $transfer->id);
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

    public function update(UpdateTransferRequest $request, Transfer $transfer)
    {
        if ($transfer->isApproved()) {
            return redirect()->route('transfers.show', $transfer->id);
        }

        DB::transaction(function () use ($request, $transfer) {
            $transfer->update($request->except('transfer'));

            for ($i = 0; $i < count($request->transfer); $i++) {
                $transfer->transferDetails[$i]->update($request->transfer[$i]);
            }
        });

        return redirect()->route('transfers.show', $transfer->id);
    }

    public function destroy(Transfer $transfer)
    {
        if ($transfer->isTransferred()) {
            return view('errors.permission_denied');
        }

        if ($transfer->isApproved() && !auth()->user()->can('Delete Approved Transfer')) {
            return view('errors.permission_denied');
        }

        $transfer->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function transfer(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        if (!$transfer->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This Transfer is not approved');
        }

        $result = DB::transaction(function () use ($transfer) {
            $result = InventoryOperationService::transfer($transfer->transferDetails);

            if (!$result['isTransferred']) {
                DB::rollBack();

                return $result;
            }

            $transfer->transfer();

            Notification::send(
                $this->notifiableUsers('Approve Transfer', $transfer->createdBy),
                new TransferMade($transfer)
            );

            return $result;
        });

        return $result['isTransferred'] ?
        redirect()->back() :
        redirect()->back()->with('failedMessage', $result['unavailableProducts']);
    }
}
