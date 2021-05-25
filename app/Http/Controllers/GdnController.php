<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreGdnRequest;
use App\Http\Requests\UpdateGdnRequest;
use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Notifications\GdnApproved;
use App\Notifications\GdnPrepared;
use App\Services\InventoryOperationService;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class GdnController extends Controller
{
    use NotifiableUsers;

    private $gdn;

    public function __construct(Gdn $gdn)
    {
        $this->authorizeResource(Gdn::class, 'gdn');

        $this->gdn = $gdn;
    }

    public function index(Gdn $gdn)
    {
        $gdns = $gdn->getAll()->load(['gdnDetails', 'createdBy', 'updatedBy', 'approvedBy', 'sale', 'customer', 'company']);

        $totalGdns = $gdn->countGdnsOfCompany();

        $totalNotApproved = $gdns->whereNull('approved_by')->count();

        $totalNotSubtracted = $gdns->where('status', 'Not Subtracted From Inventory')->whereNotNull('approved_by')->count();

        return view('gdns.index', compact('gdns', 'totalGdns', 'totalNotApproved', 'totalNotSubtracted'));
    }

    public function create(Product $product, Customer $customer, Sale $sale, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $sales = $sale->getManualSales();

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
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $sales = $sale->getManualSales();

        $warehouses = $warehouse->getAllWithoutRelations();

        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse']);

        return view('gdns.edit', compact('gdn', 'products', 'customers', 'sales', 'warehouses'));
    }

    public function update(UpdateGdnRequest $request, Gdn $gdn)
    {
        if ($gdn->isGdnApproved()) {
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
        if ($gdn->isGdnApproved() && !auth()->user()->can('Delete Approved GDN')) {
            return view('errors.permission_denied');
        }

        $gdn->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function approve(Gdn $gdn)
    {
        $this->authorize('approve', $gdn);

        $message = 'This DO/GDN is already approved';

        if (!$gdn->isGdnApproved()) {
            $message = DB::transaction(function () use ($gdn) {
                $gdn->approveGdn();

                Notification::send($this->notifiableUsers('Subtract GDN'), new GdnApproved($gdn));

                Notification::send($this->notifyCreator($gdn, $this->notifiableUsers('Subtract GDN')), new GdnApproved($gdn));

                return 'You have approved this DO/GDN successfully';
            });
        }

        return redirect()->back()->with('successMessage', $message);
    }

    public function printed(Gdn $gdn)
    {
        $this->authorize('view', $gdn);

        $gdn->load(['gdnDetails.product', 'customer', 'company', 'createdBy', 'approvedBy']);

        return view('gdns.print', compact('gdn'));
    }

    public function subtract(Gdn $gdn)
    {
        $this->authorize('subtract', $gdn);

        if (!$gdn->isGdnApproved()) {
            return redirect()->back()->with('message', 'This DO/GDN is not approved');
        }

        DB::transaction(function () use ($gdn) {
            InventoryOperationService::subtract($gdn->gdnDetails);

            $gdn->changeStatusToSubtractedFromInventory();

            Notification::send($this->notifiableUsers('Approve GDN'), new GdnSubtracted($gdn));

            Notification::send($this->notifyCreator($gdn, $this->notifiableUsers('Approve GDN')), new GdnSubtracted($gdn));
        });

        return redirect()->back();
    }
}
