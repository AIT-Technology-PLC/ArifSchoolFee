<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTenderRequest;
use App\Models\Customer;
use App\Models\Product;
use App\Models\Tender;
use App\Models\TenderStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TenderController extends Controller
{
    private $tender;

    public function __construct(Tender $tender)
    {
        $this->authorizeResource(Tender::class, 'tender');

        $this->tender = $tender;
    }

    public function index()
    {
        $tenders = $this->tender->getAll()->load(['customer', 'tenderDetails', 'createdBy', 'updatedBy']);

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
        $tender->load(['customer', 'tenderDetails.product', 'tenderChecklists']);

        return view('tenders.show', compact('tender'));
    }

    public function edit(Tender $tender, Customer $customer, TenderStatus $tenderStatus, Product $product)
    {
        $customers = $customer->getAll();

        $tenderStatuses = $tenderStatus->getAll();

        $products = $product->getProductNames();

        return view('tenders.edit', compact('tender', 'customers', 'tenderStatuses', 'products'));
    }

    public function update(Request $request, Tender $tender)
    {
        $tenderData = $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'participants' => 'nullable|integer',
            'bid_bond_amount' => 'nullable|string',
            'bid_bond_type' => 'nullable|string',
            'bid_bond_validity' => 'nullable|integer',
            'price' => 'nullable|string',
            'payment_term' => 'nullable|string',
            'published_on' => 'required|date',
            'closing_date' => 'required|date|after_or_equal:published_on',
            'opening_date' => 'required|date|after:closing_date',
            'customer_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'tender' => 'required|array',
            'tender.*.product_id' => 'required|integer',
            'tender.*.quantity' => 'required|numeric',
            'tender.*.description' => 'nullable|string',
        ]);

        $tenderData['closing_date'] = (new Carbon($tenderData['closing_date']))->toDateTimeString();
        $tenderData['opening_date'] = (new Carbon($tenderData['opening_date']))->toDateTimeString();

        $tenderData['updated_by'] = auth()->id();

        $basicTenderData = Arr::except($tenderData, ['tender', 'checklists']);
        $tenderDetailsData = $tenderData['tender'];

        DB::transaction(function () use ($tender, $basicTenderData, $tenderDetailsData) {
            $tender->update($basicTenderData);

            for ($i = 0; $i < count($tenderDetailsData); $i++) {
                $tender->tenderDetails[$i]->update($tenderDetailsData[$i]);
            }
        });

        return redirect()->route('tenders.show', $tender->id);
    }

    public function destroy(Tender $tender)
    {
        $tender->forceDelete();

        return redirect()->back()->with('deleted', 'Deleted Successfully');
    }
}
