<?php

namespace App\Http\Controllers;

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

    public function store(Request $request)
    {
        $tenderData = $request->validate([
            'code' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'participants' => 'required|integer|min:1',
            'bid_bond_amount' => 'nullable|string',
            'bid_bond_type' => 'nullable|string',
            'bid_bond_validity' => 'nullable|integer',
            'published_on' => 'required|date',
            'closing_date' => 'required|date',
            'opening_date' => 'required|date',
            'customer_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'tender' => 'required|array',
            'tender.*.product_id' => 'required|integer',
            'tender.*.quantity' => 'required|numeric',
            'tender.*.description' => 'nullable|string',
        ]);

        $tenderData['closing_date'] = (new Carbon($tenderData['closing_date']))->toDateTimeString();
        $tenderData['opening_date'] = (new Carbon($tenderData['opening_date']))->toDateTimeString();

        $tenderData['company_id'] = auth()->user()->employee->company_id;
        $tenderData['created_by'] = auth()->user()->id;
        $tenderData['updated_by'] = auth()->user()->id;

        $basicTenderData = Arr::except($tenderData, 'tender');
        $tenderDetailsData = $tenderData['tender'];

        DB::transaction(function () use ($basicTenderData, $tenderDetailsData) {
            $tender = $this->tender->create($basicTenderData);
            $tender->tenderDetails()->createMany($tenderDetailsData);
        });

        return redirect()->route('tenders.index');
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
            'participants' => 'required|integer',
            'bid_bond_amount' => 'nullable|string',
            'bid_bond_type' => 'nullable|string',
            'bid_bond_validity' => 'nullable|integer',
            'published_on' => 'required|date',
            'closing_date' => 'required|date',
            'opening_date' => 'required|date',
            'customer_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'tender' => 'required|array',
            'tender.*.product_id' => 'required|integer',
            'tender.*.quantity' => 'required|numeric',
            'tender.*.description' => 'nullable|string',
        ]);

        $tenderData['closing_date'] = (new Carbon($tenderData['closing_date']))->toDateTimeString();
        $tenderData['opening_date'] = (new Carbon($tenderData['opening_date']))->toDateTimeString();

        $tenderData['updated_by'] = auth()->user()->id;

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
