<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\ReturnDatatable;
use App\DataTables\ReturnDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreReturnRequest;
use App\Http\Requests\UpdateReturnRequest;
use App\Models\Gdn;
use App\Models\MerchandiseBatch;
use App\Models\ReturnDetail;
use App\Models\Returnn;
use App\Notifications\ReturnPrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class ReturnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Return Management');

        $this->authorizeResource(Returnn::class, 'return');
    }

    public function index(ReturnDatatable $datatable)
    {
        $datatable->builder()->setTableId('returns-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalReturns = Returnn::count();

        $totalNotApproved = Returnn::notApproved()->count();

        $totalNotAdded = Returnn::approved()->notAdded()->count();

        $totalAdded = Returnn::added()->count();

        return $datatable->render('returns.index', compact('totalReturns', 'totalNotApproved', 'totalNotAdded', 'totalAdded'));
    }

    public function create()
    {
        $warehouses = authUser()->getAllowedWarehouses('add');

        $currentReturnCode = nextReferenceNumber('returns');

        $gdns = Gdn::subtracted()->notCancelled()->with('warehouse')->orderByDesc('code')->get();

        return view('returns.create', compact('warehouses', 'currentReturnCode', 'gdns'));
    }

    public function store(StoreReturnRequest $request)
    {
        $return = DB::transaction(function () use ($request) {
            $return = Returnn::create($request->safe()->except('return'));

            $returnDetails = $return->returnDetails()->createMany($request->validated('return'));

            $deletableDetails = collect();

            foreach ($returnDetails as $returnDetail) {
                if ($returnDetail->product->isBatchable() && is_null($returnDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $returnDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $returnDetail->warehouse_id)
                        ->when($returnDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$returnDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($returnDetail->id);

                        $return->returnDetails()->create([
                            'product_id' => $returnDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $returnDetail->quantity ? $returnDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'unit_price' => $returnDetail->original_unit_price,
                            'warehouse_id' => $returnDetail->warehouse_id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $returnDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $returnDetail->quantity - $merchandiseBatch->quantity;
                            $returnDetail->quantity = $difference;
                        }
                    }
                }
            }

            ReturnDetail::whereIn('id', $deletableDetails)->forceDelete();

            Notification::send(Notifiables::byNextActionPermission('Approve Return'), new ReturnPrepared($return));

            return $return;
        });

        return redirect()->route('returns.show', $return->id);
    }

    public function show(Returnn $return, ReturnDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('return-details-datatable');

        $return->load(['returnDetails.product', 'returnDetails.warehouse', 'returnDetails.merchandiseBatch', 'customer', 'gdn']);

        return $datatable->render('returns.show', compact('return'));
    }

    public function edit(Returnn $return)
    {
        $warehouses = authUser()->getAllowedWarehouses('add');

        $gdns = Gdn::subtracted()->notCancelled()->with('warehouse')->orderByDesc('code')->get();

        $return->load(['returnDetails.product', 'returnDetails.warehouse', 'returnDetails.merchandiseBatch']);

        return view('returns.edit', compact('return', 'warehouses', 'gdns'));
    }

    public function update(UpdateReturnRequest $request, Returnn $return)
    {
        if ($return->isApproved()) {
            return redirect()->route('returns.show', $return->id)
                ->with('failedMessage', 'Approved returns cannot be edited.');
        }

        DB::transaction(function () use ($request, $return) {
            $return->update($request->safe()->except('return'));

            $return->returnDetails()->forceDelete();

            $returnDetails = $return->returnDetails()->createMany($request->validated('return'));

            $deletableDetails = collect();

            foreach ($returnDetails as $returnDetail) {
                if ($returnDetail->product->isBatchable() && is_null($returnDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $returnDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $returnDetail->warehouse_id)
                        ->when($returnDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$returnDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($returnDetail->id);

                        $return->returnDetails()->create([
                            'product_id' => $returnDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $returnDetail->quantity ? $returnDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'unit_price' => $returnDetail->original_unit_price,
                            'warehouse_id' => $returnDetail->warehouse_id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $returnDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $returnDetail->quantity - $merchandiseBatch->quantity;
                            $returnDetail->quantity = $difference;
                        }
                    }
                }
            }

            ReturnDetail::whereIn('id', $deletableDetails)->forceDelete();
        });

        return redirect()->route('returns.show', $return->id);
    }

    public function destroy(Returnn $return)
    {
        abort_if($return->isAdded(), 403);

        abort_if($return->isApproved() && !authUser()->can('Delete Approved Return'), 403);

        $return->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
