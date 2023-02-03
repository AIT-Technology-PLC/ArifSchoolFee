<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\DamageDatatable;
use App\DataTables\DamageDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDamageRequest;
use App\Http\Requests\UpdateDamageRequest;
use App\Models\Damage;
use App\Models\DamageDetail;
use App\Models\MerchandiseBatch;
use App\Notifications\DamagePrepared;
use App\Utilities\Notifiables;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class DamageController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Damage Management');

        $this->authorizeResource(Damage::class, 'damage');
    }

    public function index(DamageDatatable $datatable)
    {
        $datatable->builder()->setTableId('damages-datatable')->orderBy(1, 'desc')->orderBy(2, 'desc');

        $totalDamages = Damage::count();

        $totalNotApproved = Damage::notApproved()->count();

        $totalNotSubtracted = Damage::notSubtracted()->approved()->count();

        $totalSubtracted = Damage::subtracted()->count();

        return $datatable->render('damages.index', compact('totalDamages', 'totalNotApproved', 'totalNotSubtracted', 'totalSubtracted'));
    }

    public function create()
    {
        $warehouses = authUser()->getAllowedWarehouses('subtract');

        $currentDamageCode = nextReferenceNumber('damages');

        return view('damages.create', compact('warehouses', 'currentDamageCode'));
    }

    public function store(StoreDamageRequest $request)
    {
        $damage = DB::transaction(function () use ($request) {
            $damage = Damage::create($request->safe()->except('damage'));

            $damageDetails = $damage->damageDetails()->createMany($request->validated('damage'));

            $deletableDetails = collect();

            foreach ($damageDetails as $damageDetail) {
                if ($damageDetail->product->isBatchable() && is_null($damageDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $damageDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $damageDetail->warehouse_id)
                        ->when($damageDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$damageDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($damageDetail->id);

                        $damage->damageDetails()->create([
                            'product_id' => $damageDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $damageDetail->quantity ? $damageDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'warehouse_id' => $damageDetail->warehouse_id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $damageDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $damageDetail->quantity - $merchandiseBatch->quantity;
                            $damageDetail->quantity = $difference;
                        }
                    }
                }
            }

            DamageDetail::whereIn('id', $deletableDetails)->forceDelete();

            Notification::send(Notifiables::byNextActionPermission('Approve Damage'), new DamagePrepared($damage));

            return $damage;
        });

        return redirect()->route('damages.show', $damage->id);
    }

    public function show(Damage $damage, DamageDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('damage-details-datatable');

        return $datatable->render('damages.show', compact('damage'));
    }

    public function edit(Damage $damage)
    {
        $warehouses = authUser()->getAllowedWarehouses('subtract');

        $damage->load(['damageDetails.product', 'damageDetails.warehouse']);

        return view('damages.edit', compact('damage', 'warehouses'));
    }

    public function update(UpdateDamageRequest $request, Damage $damage)
    {
        if ($damage->isApproved()) {
            return redirect()->route('damages.show', $damage->id)
                ->with('failedMessage', 'Approved damages cannot be edited.');
        }

        DB::transaction(function () use ($request, $damage) {
            $damage->update($request->safe()->except('damage'));

            $damage->damageDetails()->forceDelete();

            $damageDetails = $damage->damageDetails()->createMany($request->validated('damage'));

            $deletableDetails = collect();

            foreach ($damageDetails as $damageDetail) {
                if ($damageDetail->product->isBatchable() && is_null($damageDetail->merchandise_batch_id)) {
                    $merchandiseBatches = MerchandiseBatch::where('quantity', '>', 0)
                        ->whereRelation('merchandise', 'product_id', $damageDetail->product_id)
                        ->whereRelation('merchandise', 'warehouse_id', $damageDetail->warehouse_id)
                        ->when($damageDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'DESC'))
                        ->when(!$damageDetail->product->isLifo(), fn($q) => $q->orderBy('expires_on', 'ASC'))
                        ->get();

                    foreach ($merchandiseBatches as $merchandiseBatch) {
                        $deletableDetails->push($damageDetail->id);

                        $damage->damageDetails()->create([
                            'product_id' => $damageDetail->product_id,
                            'quantity' => $merchandiseBatch->quantity >= $damageDetail->quantity ? $damageDetail->quantity : $merchandiseBatch->quantity,
                            'merchandise_batch_id' => $merchandiseBatch->id,
                            'warehouse_id' => $damageDetail->warehouse_id,
                        ]
                        );

                        if ($merchandiseBatch->quantity >= $damageDetail->quantity) {
                            $difference = 0;

                            break;
                        } else {
                            $difference = $damageDetail->quantity - $merchandiseBatch->quantity;
                            $damageDetail->quantity = $difference;
                        }
                    }
                }
            }

            DamageDetail::whereIn('id', $deletableDetails)->forceDelete();
        });

        return redirect()->route('damages.show', $damage->id);
    }

    public function destroy(Damage $damage)
    {
        abort_if($damage->isSubtracted(), 403);

        abort_if($damage->isApproved() && !authUser()->can('Delete Approved Damage'), 403);

        $damage->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
