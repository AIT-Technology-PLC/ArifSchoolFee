<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\GdnDatatable;
use App\DataTables\GdnDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGdnRequest;
use App\Http\Requests\UpdateGdnRequest;
use App\Models\Gdn;
use App\Models\GdnDetail;
use App\Models\MerchandiseBatch;
use App\Models\Sale;
use App\Models\Siv;
use App\Notifications\GdnPrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GdnController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->authorizeResource(Gdn::class, 'gdn');
    }

    public function index(GdnDatatable $datatable)
    {
        $datatable->builder()->setTableId('gdns-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalGdns = Gdn::count();

        $totalNotApproved = Gdn::notApproved()->count();

        $totalNotSubtracted = Gdn::notSubtracted()->notCancelled()->approved()->count();

        $totalSubtracted = Gdn::subtracted()->notCancelled()->notClosed()->count();

        return $datatable->render('gdns.index', compact('totalGdns', 'totalNotApproved', 'totalNotSubtracted', 'totalSubtracted'));
    }

    public function create()
    {
        $sales = Sale::latest('id')->get();

        $warehouses = authUser()->getAllowedWarehouses('sales');

        $currentGdnCode = nextReferenceNumber('gdns');

        return view('gdns.create', compact('sales', 'warehouses', 'currentGdnCode'));
    }

    public function store(StoreGdnRequest $request)
    {
        $gdn = DB::transaction(function () use ($request) {
            $gdn = Gdn::create($request->safe()->except('gdn'));

            $gdnDetails = $gdn->gdnDetails()->createMany($request->validated('gdn'));

            $deletableDetails = collect();

            foreach ($gdnDetails as $gdnDetail) {
                if ($gdnDetail->product->isBatchable() && is_null($gdnDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $gdnDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $gdnDetail->warehouse_id)
                        ->when($gdnDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$gdnDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($gdnDetail->id);

                        $gdn->gdnDetails()->create([
                            'product_id' => $gdnDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $gdnDetail->quantity ? $gdnDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'unit_price' => $gdnDetail->original_unit_price,
                            'warehouse_id' => $gdnDetail->warehouse_id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $gdnDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $gdnDetail->quantity - $merchandiseBatch->quantity;
                            $gdnDetail->quantity = $difference;
                        }
                    }
                }
            }

            GdnDetail::whereIn('id', $deletableDetails)->forceDelete();

            Notification::send(Notifiables::byNextActionPermission('Approve GDN'), new GdnPrepared($gdn));

            return $gdn;
        }, 2);

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function show(Gdn $gdn, GdnDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('gdn-details-datatable');

        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse', 'gdnDetails.merchandiseBatch', 'customer', 'contact', 'sale']);

        $sivs = Siv::where('purpose', 'DO')->where('ref_num', $gdn->code)->get();

        return $datatable->render('gdns.show', compact('gdn', 'sivs'));
    }

    public function edit(Gdn $gdn)
    {
        $sales = Sale::latest('code')->get();

        $warehouses = authUser()->getAllowedWarehouses('sales');

        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse', 'gdnDetails.merchandiseBatch']);

        return view('gdns.edit', compact('gdn', 'sales', 'warehouses'));
    }

    public function update(UpdateGdnRequest $request, Gdn $gdn)
    {
        if ($gdn->reservation()->exists()) {
            return redirect()->route('gdns.show', $gdn->id)
                ->with('failedMessage', 'Delivery orders issued from reservations cannot be edited.');
        }

        if ($gdn->isCancelled()) {
            return back()->with('failedMessage', 'You can not modify a Delivery order that is cancelled.');
        }

        if ($gdn->isApproved()) {
            $gdn->update($request->safe()->only('sale_id', 'description'));

            return redirect()->route('gdns.show', $gdn->id);
        }

        DB::transaction(function () use ($request, $gdn) {
            $gdn->update($request->safe()->except('gdn'));

            $gdn->gdnDetails()->forceDelete();

            $gdnDetails = $gdn->gdnDetails()->createMany($request->validated('gdn'));

            $deletableDetails = collect();

            foreach ($gdnDetails as $gdnDetail) {
                if ($gdnDetail->product->isBatchable() && is_null($gdnDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $gdnDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $gdnDetail->warehouse_id)
                        ->when($gdnDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$gdnDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($gdnDetail->id);

                        $gdn->gdnDetails()->create([
                            'product_id' => $gdnDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $gdnDetail->quantity ? $gdnDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'unit_price' => $gdnDetail->original_unit_price,
                            'warehouse_id' => $gdnDetail->warehouse_id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $gdnDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $gdnDetail->quantity - $merchandiseBatch->quantity;
                            $gdnDetail->quantity = $difference;
                        }
                    }
                }
            }

            GdnDetail::whereIn('id', $deletableDetails)->forceDelete();
        }, 2);

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function destroy(Gdn $gdn)
    {
        if ($gdn->reservation()->exists()) {
            return back()->with('failedMessage', 'Delivery orders issued from reservations cannot be deleted.');
        }

        abort_if($gdn->isSubtracted() || $gdn->isCancelled(), 403);

        abort_if($gdn->isApproved() && !authUser()->can('Delete Approved GDN'), 403);

        $gdn->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
