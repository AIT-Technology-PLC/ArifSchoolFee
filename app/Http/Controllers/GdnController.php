<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGdnRequest;
use App\Http\Requests\UpdateGdnRequest;
use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Notifications\GdnPrepared;
use App\Traits\NotifiableUsers;
use App\Traits\SubtractInventory;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GdnController extends Controller
{
    use NotifiableUsers, SubtractInventory;

    private $gdn;

    private $permission;

    public function __construct(Gdn $gdn)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Gdn Management');

        $this->authorizeResource(Gdn::class, 'gdn');

        $this->gdn = $gdn;

        $this->permission = 'Subtract GDN';
    }

    public function index(Gdn $gdn)
    {
        $gdns = $gdn->getAll()->load(['gdnDetails', 'createdBy', 'updatedBy', 'approvedBy', 'sale', 'customer', 'company']);

        $totalGdns = $gdn->countGdnsOfCompany();

        $totalNotApproved = $gdns->whereNull('approved_by')->count();

        $totalNotSubtracted = $gdns->where('status', 'Not Subtracted From Inventory')->whereNotNull('approved_by')->count();

        $totalSubtracted = $gdns->where('status', 'Subtracted From Inventory')->count();

        return view('gdns.index', compact('gdns', 'totalGdns', 'totalNotApproved', 'totalNotSubtracted', 'totalSubtracted'));
    }

    public function create(Product $product, Customer $customer, Sale $sale, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $sales = $sale->getAll();

        $warehouses = $warehouse->getAllWithoutRelations();

        $currentGdnCode = (Gdn::select('code')->companyGdn()->latest()->first()->code) ?? 0;

        return view('gdns.create', compact('products', 'customers', 'sales', 'warehouses', 'currentGdnCode'));
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

    public function edit(Gdn $gdn, Product $product, Customer $customer, Sale $sale, Warehouse $warehouse)
    {
        if ($gdn->reservation) {
            return redirect()->back()->with('failedMessage', 'You cannot edit a DO that belongs to a reservation.');
        }

        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $sales = $sale->getAll();

        $warehouses = $warehouse->getAllWithoutRelations();

        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse']);

        return view('gdns.edit', compact('gdn', 'products', 'customers', 'sales', 'warehouses'));
    }

    public function update(UpdateGdnRequest $request, Gdn $gdn)
    {
        if ($gdn->reservation) {
            return redirect()->route('gdns.show', $gdn->id)
                ->with('failedMessage', 'You cannot edit a DO that belongs to a reservation.');
        }

        if ($gdn->isApproved()) {
            $gdn->update($request->only('sale_id', 'updated_by'));

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
            return redirect()->back()->with('failedMessage', "You cannot delete a DO that belongs to a reservation, instead cancel the reservation.");
        }

        if ($gdn->isSubtracted()) {
            return view('errors.permission_denied');
        }

        if ($gdn->isApproved() && !auth()->user()->can('Delete Approved GDN')) {
            return view('errors.permission_denied');
        }

        $gdn->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function printed(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        $gdn->load(['gdnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return view('gdns.print', compact('gdn'));
    }
}
