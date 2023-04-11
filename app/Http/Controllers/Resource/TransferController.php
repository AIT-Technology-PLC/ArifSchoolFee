<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\TransferDatatable;
use App\DataTables\TransferDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTransferRequest;
use App\Http\Requests\UpdateTransferRequest;
use App\Models\MerchandiseBatch;
use App\Models\SivDetail;
use App\Models\Transfer;
use App\Models\TransferDetail;
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
            $transfer = Transfer::create($request->safe()->except('transfer'));

            $transferDetails = $transfer->transferDetails()->createMany($request->validated('transfer'));

            $deletableDetails = collect();

            foreach ($transferDetails as $transferDetail) {
                if ($transferDetail->product->isBatchable() && is_null($transferDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $transferDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $transfer->transferred_from)
                        ->when($transferDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$transferDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($transferDetail->id);

                        $transfer->transferDetails()->create([
                            'product_id' => $transferDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $transferDetail->quantity ? $transferDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $transferDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $transferDetail->quantity - $merchandiseBatch->quantity;
                            $transferDetail->quantity = $difference;
                        }
                    }
                }
            }

            TransferDetail::whereIn('id', $deletableDetails)->forceDelete();

            Notification::send(Notifiables::byNextActionPermission('Approve Transfer'), new TransferPrepared($transfer));

            return $transfer;
        });

        return redirect()->route('transfers.show', $transfer->id);
    }

    public function show(Transfer $transfer, TransferDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('transfer-details');

        $transfer->load(['transferDetails.product', 'transferDetails.merchandiseBatch', 'transferredFrom', 'transferredTo']);

        $sivDetails = SivDetail::with('product', 'warehouse', 'siv')->whereRelation('siv', 'purpose', 'Transfer')->whereRelation('siv', 'ref_num', $transfer->code)->get();

        return $datatable->render('transfers.show', compact('transfer', 'sivDetails'));
    }

    public function edit(Transfer $transfer)
    {
        $transfer->load(['transferDetails.product', 'transferDetails.merchandiseBatch', 'transferredFrom', 'transferredTo']);

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
            $transfer->update($request->safe()->except('transfer'));

            $transfer->transferDetails()->forceDelete();

            $transferDetails = $transfer->transferDetails()->createMany($request->validated('transfer'));

            $deletableDetails = collect();

            foreach ($transferDetails as $transferDetail) {
                if ($transferDetail->product->isBatchable() && is_null($transferDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $transferDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $transfer->transferred_from)
                        ->when($transferDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$transferDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($transferDetail->id);

                        $transfer->transferDetails()->create([
                            'product_id' => $transferDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $transferDetail->quantity ? $transferDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $transferDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $transferDetail->quantity - $merchandiseBatch->quantity;
                            $transferDetail->quantity = $difference;
                        }
                    }
                }
            }

            TransferDetail::whereIn('id', $deletableDetails)->forceDelete();
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
