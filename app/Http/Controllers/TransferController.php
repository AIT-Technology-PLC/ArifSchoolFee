<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferRequest;
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
        $this->middleware('isFeatureAccessible:Transfer Management');

        $this->authorizeResource(Transfer::class, 'transfer');

        $this->transfer = $transfer;

        $this->permission = 'Make Transfer';
    }

    public function index(Transfer $transfer)
    {
        $transfers = $transfer->getAll()->load(['createdBy', 'updatedBy', 'approvedBy', 'transferredFrom', 'transferredTo']);

        $totalTransferred = $transfers->whereNotNull('added_by')->count();

        $totalSubtracted = $transfers->whereNotNull('subtracted_by')->whereNull('added_by')->count();

        $totalApproved = $transfers->whereNotNull('approved_by')->whereNull('subtracted_by')->count();

        $totalNotApproved = $transfers->whereNull('approved_by')->count();

        $totalTransfers = $transfers->count();

        return view('transfers.index', compact('transfers', 'totalTransfers', 'totalTransferred', 'totalSubtracted', 'totalApproved', 'totalNotApproved'));
    }

    public function create(Warehouse $warehouse)
    {
        $fromWarehouses = $warehouse->getAllWithoutRelations();

        $toWarehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->assignedWarehouse());

        $currentTransferCode = (Transfer::select('code')->companyTransfer()->latest()->first()->code) ?? 0;

        return view('transfers.create', compact('fromWarehouses', 'toWarehouses', 'currentTransferCode'));
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
        $transfer->load(['transferDetails.product', 'transferredFrom', 'transferredTo']);

        return view('transfers.show', compact('transfer'));
    }

    public function edit(Transfer $transfer, Warehouse $warehouse)
    {
        $transfer->load(['transferDetails.product', 'transferredFrom', 'transferredTo']);

        $fromWarehouses = $warehouse->getAllWithoutRelations();

        $toWarehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->assignedWarehouse());

        return view('transfers.edit', compact('transfer', 'fromWarehouses', 'toWarehouses'));
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
        if ($transfer->isSubtracted()) {
            abort(403);
        }

        if ($transfer->isApproved() && !auth()->user()->can('Delete Approved Transfer')) {
            abort(403);
        }

        $transfer->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function transfer(Transfer $transfer)
    {
        $this->authorize('transfer', $transfer);

        abort_if(
            !$transfer->isSubtracted() && auth()->user()->warehouse_id != $transfer->transferred_from,
            403
        );

        abort_if(
            $transfer->isSubtracted() && !auth()->user()->addWarehouses()->contains($transfer->transferred_to),
            403
        );

        if (!$transfer->isApproved()) {
            return redirect()->back()->with('failedMessage', 'This Transfer is not approved');
        }

        $result = DB::transaction(function () use ($transfer) {
            $result = InventoryOperationService::transfer($transfer->transferDetails, $transfer->isSubtracted());

            if (!$result['isTransferred']) {
                DB::rollBack();

                return $result;
            }

            $transfer->isSubtracted() ? $transfer->add() : $transfer->subtract();

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
