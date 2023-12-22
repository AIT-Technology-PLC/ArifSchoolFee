<?php

namespace App\Http\Controllers\Resource;

use App\Actions\AutoBatchStoringAction;
use App\DataTables\GdnDatatable;
use App\DataTables\GdnDetailDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGdnRequest;
use App\Http\Requests\UpdateGdnRequest;
use App\Models\Gdn;
use App\Models\Sale;
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

        $totalSubtracted = Gdn::subtracted()->notCancelled()->count();

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

            $gdn->gdnDetails()->createMany($request->validated('gdn'));

            AutoBatchStoringAction::execute($gdn, $request->validated('gdn'), 'gdnDetails');

            $gdn->createCustomFields($request->validated('customField'));

            Notification::send(Notifiables::byNextActionPermission('Approve GDN'), new GdnPrepared($gdn));

            return $gdn;
        }, 2);

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function show(Gdn $gdn, GdnDetailDatatable $datatable)
    {
        $datatable->builder()->setTableId('gdn-details-datatable');

        $gdn->load(['gdnDetails.product.tax', 'gdnDetails.warehouse', 'gdnDetails.merchandiseBatch', 'customer', 'contact', 'sale', 'customFieldValues.customField', 'sivs.sivDetails']);

        return $datatable->render('gdns.show', compact('gdn'));
    }

    public function edit(Gdn $gdn)
    {
        if ($gdn->belongsToTransaction()) {
            return back()->with('failedMessage', 'Delivery orders issued from other transaction cannot be edited.');
        }

        $sales = Sale::latest('code')->get();

        $warehouses = authUser()->getAllowedWarehouses('sales');

        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse', 'gdnDetails.merchandiseBatch']);

        return view('gdns.edit', compact('gdn', 'sales', 'warehouses'));
    }

    public function update(UpdateGdnRequest $request, Gdn $gdn)
    {
        if ($gdn->belongsToTransaction()) {
            return redirect()->route('gdns.show', $gdn->id)
                ->with('failedMessage', 'Delivery orders issued from other transaction cannot be edited.');
        }

        if ($gdn->isCancelled()) {
            return back()->with('failedMessage', 'You can not modify a Delivery order that is cancelled.');
        }

        if ($gdn->isSubtracted()) {
            $gdn->update($request->safe()->only('description'));
            return redirect()->route('gdns.show', $gdn->id);
        }

        DB::transaction(function () use ($request, $gdn) {
            $gdn->update($request->safe()->except('gdn'));

            $gdn->gdnDetails()->forceDelete();

            $gdn->gdnDetails()->createMany($request->validated('gdn'));

            AutoBatchStoringAction::execute($gdn, $request->validated('gdn'), 'gdnDetails');

            $gdn->createCustomFields($request->validated('customField'));
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

        $gdn->proformaInvoice?->proformaInvoiceable()->dissociate($gdn)->save();

        $gdn->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
