<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenderRequest;
use App\Http\Requests\UpdateTenderRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Tender;
use App\Models\TenderStatus;
use App\Notifications\TenderStatusChanged;
use App\Traits\NotifiableUsers;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TenderController extends Controller
{
    use NotifiableUsers;

    private $tender;

    public function __construct(Tender $tender)
    {
        $this->middleware('\App\Http\Middleware\AllowOnlyEnabledFeatures:Tender Management');

        $this->authorizeResource(Tender::class, 'tender');

        $this->tender = $tender;
    }

    public function index()
    {
        $tenders = $this->tender->getAll()
            ->load(['customer', 'tenderDetails', 'tenderChecklists', 'createdBy', 'updatedBy']);

        $totalTenders = $this->tender->countTendersOfCompany();

        return view('tenders.index', compact('tenders', 'totalTenders'));
    }

    public function create(Customer $customer, TenderStatus $tenderStatus, Product $product)
    {
        $customers = $customer->getAll();

        $tenderStatuses = $tenderStatus->getAll();

        $products = $product->getProductNames();

        return view('tenders.create', compact('customers', 'tenderStatuses', 'products'));
    }

    public function store(StoreTenderRequest $request)
    {
        $tender = DB::transaction(function () use ($request) {
            $tender = $this->tender->create($request->except('tender'));

            $tender->tenderDetails()->createMany($request->tender);

            return $tender;
        });

        return redirect()->route('tenders.show', $tender);
    }

    public function show(Tender $tender)
    {
        $tender->load(['customer', 'tenderDetails.product', 'tenderChecklists.generalTenderChecklist.tenderChecklistType']);

        return view('tenders.show', compact('tender'));
    }

    public function edit(Tender $tender, Customer $customer, TenderStatus $tenderStatus, Product $product)
    {
        $tender->load(['tenderDetails.product']);

        $customers = $customer->getAll();

        $tenderStatuses = $tenderStatus->getAll();

        $products = $product->getProductNames();

        return view('tenders.edit', compact('tender', 'customers', 'tenderStatuses', 'products'));
    }

    public function update(UpdateTenderRequest $request, Tender $tender)
    {
        DB::transaction(function () use ($request, $tender) {
            $originalStatus = $tender->status;

            $tender->update($request->except('tender'));

            for ($i = 0; $i < count($request->tender); $i++) {
                $tender->tenderDetails[$i]->update($request->tender[$i]);
            }

            if ($tender->wasChanged('status')) {
                Notification::send($this->notifiableUsers('Read Tender'), new TenderStatusChanged($originalStatus, $tender));
            }
        });

        return redirect()->route('tenders.show', $tender->id);
    }

    public function destroy(Tender $tender)
    {
        $tender->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }

    public function printed(Tender $tender)
    {
        $this->authorize('view', $tender);

        return view('tenders.print', compact('tender'));
    }
}
