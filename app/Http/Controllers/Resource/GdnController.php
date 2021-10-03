<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGdnRequest;
use App\Http\Requests\UpdateGdnRequest;
use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Notifications\GdnPrepared;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GdnController extends Controller
{
    use NotifiableUsers;

    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->authorizeResource(Gdn::class, 'gdn');
    }

    public function index()
    {
        $gdns = (new Gdn)->getAll()->load(['gdnDetails', 'createdBy', 'updatedBy', 'approvedBy', 'sale', 'customer']);

        $totalGdns = Gdn::count();

        $totalNotApproved = Gdn::whereNull('approved_by')->count();

        $totalNotSubtracted = Gdn::whereNull('subtracted_by')->whereNotNull('approved_by')->count();

        $totalSubtracted = Gdn::whereNotNull('subtracted_by')->count();

        return view('gdns.index', compact('gdns', 'totalGdns', 'totalNotApproved', 'totalNotSubtracted', 'totalSubtracted'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $sales = (new Sale)->getAll();

        $warehouses = Warehouse::orderBy('name')->whereIn('id', auth()->user()->subtractWarehouses())->get(['id', 'name']);

        $currentGdnCode = Gdn::byBranch()->max('code') + 1;

        return view('gdns.create', compact('customers', 'sales', 'warehouses', 'currentGdnCode'));
    }

    public function store(StoreGdnRequest $request)
    {
        $gdn = DB::transaction(function () use ($request) {
            $gdn = Gdn::create($request->except('gdn'));

            $gdn->gdnDetails()->createMany($request->gdn);

            Notification::send($this->notifiableUsers('Approve GDN'), new GdnPrepared($gdn));

            return $gdn;
        });

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function show(Gdn $gdn)
    {
        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse', 'customer', 'sale']);

        return view('gdns.show', compact('gdn'));
    }

    public function edit(Gdn $gdn)
    {
        if ($gdn->reservation()->exists()) {
            return back()->with('failedMessage', 'You cannot edit a DO that belongs to a reservation.');
        }

        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $sales = (new Sale)->getAll();

        $warehouses = Warehouse::orderBy('name')->whereIn('id', auth()->user()->subtractWarehouses())->get(['id', 'name']);

        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse']);

        return view('gdns.edit', compact('gdn', 'customers', 'sales', 'warehouses'));
    }

    public function update(UpdateGdnRequest $request, Gdn $gdn)
    {
        if ($gdn->reservation()->exists()) {
            return redirect()->route('gdns.show', $gdn->id)
                ->with('failedMessage', 'You cannot edit a DO that belongs to a reservation.');
        }

        if ($gdn->isApproved()) {
            $gdn->update($request->only('sale_id', 'description'));

            return redirect()->route('gdns.show', $gdn->id);
        }

        DB::transaction(function () use ($request, $gdn) {
            $gdn->update($request->except('gdn'));

            for ($i = 0; $i < count($request->gdn); $i++) {
                $gdn->gdnDetails[$i]->update($request->gdn[$i]);
            }
        });

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function destroy(Gdn $gdn)
    {
        if ($gdn->reservation()->exists()) {
            return back()
                ->with('failedMessage', "You cannot delete a DO that belongs to a reservation, instead cancel the reservation.");
        }

        if ($gdn->isSubtracted()) {
            abort(403);
        }

        if ($gdn->isApproved() && !auth()->user()->can('Delete Approved GDN')) {
            abort(403);
        }

        $gdn->forceDelete();

        return back()->with('deleted', 'Deleted Successfully');
    }
}
