<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferRequest;
use App\Models\Product;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Notifications\TransferApproved;
use App\Notifications\TransferMade;
use App\Notifications\TransferPrepared;
use App\Services\InventoryOperationService;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TransferController extends Controller
{
    use NotifiableUsers;

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
        if ($transfer->isTransferApproved()) {
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
        if ($transfer->isTransferApproved() && !auth()->user()->can('Delete Approved Transfer')) {
            return view('errors.permission_denied');
        }

        $transfer->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function approve(Transfer $transfer)
    {
        $this->authorize('approve', $transfer);

        $message = 'This Transfer is already approved';

        if (!$transfer->isTransferApproved()) {
            $message = DB::transaction(function () use ($transfer) {
                $transfer->approveTransfer();

                Notification::send($this->notifiableUsers('Make Transfer'), new TransferApproved($transfer));

                Notification::send($this->notifyCreator($transfer, $this->notifiableUsers('Make Transfer')), new TransferApproved($transfer));

                return 'You have approved this Transfer successfully';
            });
        }

        return redirect()->back()->with('successMessage', $message);
    }

    public function transfer(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        if (!$transfer->isTransferApproved()) {
            return redirect()->back()->with('failedMessage', 'This Transfer is not approved');
        }

        $result = DB::transaction(function () use ($transfer) {
            $result = InventoryOperationService::transfer($transfer->transferDetails);

            if (!$result['isTransffered']) {
                DB::rollBack();

                return $result;
            }

            $transfer->changeStatusToTransferred();

            Notification::send($this->notifiableUsers('Approve Transfer'), new TransferMade($transfer));

            Notification::send($this->notifyCreator($transfer, $this->notifiableUsers('Approve Transfer')), new TransferMade($transfer));

            return $result;
        });

        return $result['isTransffered'] ?
        redirect()->back() :
        redirect()->back()->with('failedMessage', $result['unavailableProducts']);
    }
}
