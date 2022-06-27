<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\TransferDatatable;
use App\DataTables\TransferDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferRequest;
use App\Models\Siv;
use App\Models\Transfer;
use App\Models\Warehouse;
use App\Notifications\TransferPrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TransferController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Transfer Management');

        $this->authorizeResource(Transfer::class, 'transfer');
    }

    public function index(TransferDatatable $datatable)
    {
        $datatable->builder()->setTableId('transfers-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalAdded = Transfer::added()->count();

        $totalSubtracted = Transfer::subtracted()->notAdded()->count();

        $totalApproved = Transfer::approved()->notSubtracted()->count();

        $totalNotApproved = Transfer::notApproved()->count();

        $totalTransfers = Transfer::count();

        return $datatable->render('transfers.index', compact('totalTransfers', 'totalAdded', 'totalSubtracted', 'totalApproved', 'totalNotApproved'));
    }

    public function create()
    {
        $fromWarehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $toWarehouses = authUser()->getAllowedWarehouses('add');

        $currentTransferCode = nextReferenceNumber('transfers');

        return view('transfers.create', compact('fromWarehouses', 'toWarehouses', 'currentTransferCode'));
    }

    public function store(StoreTransferRequest $request)
    {
        $transfer = DB::transaction(function () use ($request) {
            $transfer = Transfer::create($request->except('transfer'));

            $transfer->transferDetails()->createMany($request->transfer);

            Notification::send(Notifiables::byNextActionPermission('Approve Transfer'), new TransferPrepared($transfer));

            return $transfer;
        });

        return redirect()->route('transfers.show', $transfer->id);
    }

    public function show(Transfer $transfer, TransferDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('transfer-details');

        $transfer->load(['transferDetails.product', 'transferredFrom', 'transferredTo']);

        $sivs = Siv::where('purpose', 'Transfer')->where('ref_num', $transfer->code)->get();

        return $datatable->render('transfers.show', compact('transfer', 'sivs'));
    }

    public function edit(Transfer $transfer)
    {
        $transfer->load(['transferDetails.product', 'transferredFrom', 'transferredTo']);

        $fromWarehouses = Warehouse::orderBy('name')->get(['id', 'name']);

        $toWarehouses = authUser()->getAllowedWarehouses('add');

        return view('transfers.edit', compact('transfer', 'fromWarehouses', 'toWarehouses'));
    }

    public function update(UpdateTransferRequest $request, Transfer $transfer)
    {
        if ($transfer->isApproved()) {
            return redirect()->route('transfers.show', $transfer->id)
                ->with('failedMessage', 'Approved transfers cannot be edited.');
        }

        DB::transaction(function () use ($request, $transfer) {
            $transfer->update($request->except('transfer'));

            $transfer->transferDetails()->forceDelete();

            $transfer->transferDetails()->createMany($request->safe()['transfer']);
        });

        return redirect()->route('transfers.show', $transfer->id);
    }

    public function destroy(Transfer $transfer)
    {
        abort_if($transfer->isSubtracted(), 403);

        abort_if($transfer->isApproved() && !authUser()->can('Delete Approved Transfer'), 403);

        $transfer->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}