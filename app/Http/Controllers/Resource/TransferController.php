<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferRequest;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Notifications\TransferPrepared;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Transfer Management');

        $this->authorizeResource(Transfer::class, 'transfer');
    }

    public function index()
    {
        $transfers = (new Transfer)->getAll()->load(['createdBy', 'updatedBy', 'approvedBy', 'transferredFrom', 'transferredTo']);

        $totalTransferred = Transfer::added()->count();

        $totalSubtracted = Transfer::subtracted()->notAdded()->count();

        $totalApproved = Transfer::approved()->notSubtracted()->count();

        $totalNotApproved = Transfer::notApproved()->count();

        $totalTransfers = Transfer::count();

        return view('transfers.index', compact('transfers', 'totalTransfers', 'totalTransferred', 'totalSubtracted', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $fromWarehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $toWarehouses = auth()->user()->getAllowedWarehouses('add');

        $currentTransferCode = Transfer::byBranch()->max('code') + 1;

        return view('transfers.create', compact('fromWarehouses', 'toWarehouses', 'currentTransferCode'));
    }

    public function store(StoreTransferRequest $request)
    {
        $transfer = DB::transaction(function () use ($request) {
            $transfer = Transfer::create($request->except('transfer'));

            $transfer->transferDetails()->createMany($request->transfer);

            Notification::send(notifiables('Approve Transfer'), new TransferPrepared($transfer));

            return $transfer;
        });

        return redirect()->route('transfers.show', $transfer->id);
    }

    public function show(Transfer $transfer)
    {
        $transfer->load(['transferDetails.product', 'transferredFrom', 'transferredTo']);

        return view('transfers.show', compact('transfer'));
    }

    public function edit(Transfer $transfer)
    {
        $transfer->load(['transferDetails.product', 'transferredFrom', 'transferredTo']);

        $fromWarehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $toWarehouses = auth()->user()->getAllowedWarehouses('add');

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

        return back()->with('deleted', 'Deleted successfully.');
    }
}
