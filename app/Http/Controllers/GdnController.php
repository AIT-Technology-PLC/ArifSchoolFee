<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGdnRequest;
use App\Http\Requests\UpdateGdnRequest;
use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Notifications\GdnPrepared;
use App\Traits\ApproveInventory;
use App\Traits\NotifiableUsers;
use App\Traits\SubtractInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GdnController extends Controller
{
    use NotifiableUsers, SubtractInventory, ApproveInventory;

    private $gdn;

    private $permission;

    public function __construct(Gdn $gdn)
    {
        $this->middleware('isFeatureAccessible:Gdn Management');

        $this->authorizeResource(Gdn::class, 'gdn');

        $this->gdn = $gdn;

        $this->permission = 'Subtract GDN';
    }

    public function index(Gdn $gdn)
    {
        $gdns = $gdn->getAll()->load(['gdnDetails', 'createdBy', 'updatedBy', 'approvedBy', 'sale', 'customer', 'company']);

        $totalGdns = $gdns->count();

        $totalNotApproved = $gdns->whereNull('approved_by')->count();

        $totalNotSubtracted = $gdns->whereNull('subtracted_by')->whereNotNull('approved_by')->count();

        $totalSubtracted = $gdns->whereNotNull('subtracted_by')->count();

        return view('gdns.index', compact('gdns', 'totalGdns', 'totalNotApproved', 'totalNotSubtracted', 'totalSubtracted'));
    }

    public function create(Customer $customer, Sale $sale, Warehouse $warehouse)
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $sales = $sale->getAll();

        $warehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->subtractWarehouses());

        $currentGdnCode = Gdn::byBranch()->max('code') + 1;

        return view('gdns.create', compact('customers', 'sales', 'warehouses', 'currentGdnCode'));
    }

    public function store(StoreGdnRequest $request)
    {
        $gdn = DB::transaction(function () use ($request) {
            $gdn = $this->gdn->create($request->except('gdn'));

            $gdn->gdnDetails()->createMany($request->gdn);

            Notification::send($this->notifiableUsers('Approve GDN'), new GdnPrepared($gdn));

            return $gdn;
        });

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function show(Gdn $gdn)
    {
        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse', 'customer', 'sale', 'company']);

        return view('gdns.show', compact('gdn'));
    }

    public function edit(Gdn $gdn, Customer $customer, Sale $sale, Warehouse $warehouse)
    {
        if ($gdn->reservation) {
            return redirect()->back()->with('failedMessage', 'You cannot edit a DO that belongs to a reservation.');
        }

        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $sales = $sale->getAll();

        $warehouses = $warehouse->getAllWithoutRelations()->whereIn('id', auth()->user()->subtractWarehouses());

        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse']);

        return view('gdns.edit', compact('gdn', 'customers', 'sales', 'warehouses'));
    }

    public function update(UpdateGdnRequest $request, Gdn $gdn)
    {
        if ($gdn->reservation) {
            return redirect()->route('gdns.show', $gdn->id)
                ->with('failedMessage', 'You cannot edit a DO that belongs to a reservation.');
        }

        if ($gdn->isApproved()) {
            $gdn->update($request->only('sale_id', 'description', 'updated_by'));

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
        if ($gdn->reservation) {
            return redirect()->back()
                ->with('failedMessage', "You cannot delete a DO that belongs to a reservation, instead cancel the reservation.");
        }

        if ($gdn->isSubtracted()) {
            abort(403);
        }

        if ($gdn->isApproved() && !auth()->user()->can('Delete Approved GDN')) {
            abort(403);
        }

        $gdn->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function printed(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        $gdn->load(['gdnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return \PDF::loadView('gdns.print', compact('gdn'))
            ->setPaper('a4', 'portrait')
            ->stream();
    }
}
