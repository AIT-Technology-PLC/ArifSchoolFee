<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GeneralTenderChecklist;
use App\Models\Product;
use App\Models\Tender;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TenderController extends Controller
{
    private $tender;

    public function __construct(Tender $tender)
    {
        $this->tender = $tender;
    }

    public function index()
    {
        $tenders = $this->tender->getAll()->load(['customer', 'tenderDetails', 'tenderChecklists', 'company', 'createdBy', 'updatedBy']);

        $totalTenders = $this->tender->countTendersOfCompany();

        return view('tenders.index', compact('tenders', 'totalTenders'));
    }

    public function create(Customer $customer, GeneralTenderChecklist $generalTenderChecklist, Product $product)
    {
        $customers = $customer->getAll();

        $generalTenderChecklists = $generalTenderChecklist->getAll();

        $products = $product->getProductNames();

        return view('tenders.create', compact('customers', 'generalTenderChecklists', 'products'));
    }

    public function store(Request $request)
    {
        $tenderData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'participants' => 'required|integer',
            'published_on' => 'required|date',
            'closing_date' => 'required|date',
            'opening_date' => 'required|date',
            'customer_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'tender' => 'required|array',
            'tender.*.product_id' => 'required|integer',
            'tender.*.quantity' => 'required|numeric',
            'tender.*.unit_price' => 'required|numeric',
            'tender.*.description' => 'nullable|string',
            'checklists' => 'required|array',
        ]);

        $tenderData['company_id'] = auth()->user()->employee->company_id;
        $tenderData['created_by'] = auth()->user()->id;
        $tenderData['updated_by'] = auth()->user()->id;

        $basicTenderData = Arr::except($tenderData, ['tender', 'checklists']);
        $tenderDetailsData = $tenderData['tender'];
        $tenderChecklistsData = $tenderData['checklists'];

        DB::transaction(function () use ($basicTenderData, $tenderDetailsData, $tenderChecklistsData) {
            $tender = $this->tender->create($basicTenderData);
            $tender->tenderDetails()->createMany($tenderDetailsData);
            $tender->tenderChecklists()->createMany($tenderChecklistsData);
        });

        return redirect()->route('tenders.index');
    }

    public function show(Tender $tender)
    {
        $tender->load(['customer', 'tenderDetails.product', 'tenderChecklists', 'company']);

        return view('tenders.show', compact('tender'));
    }

    public function edit(Tender $tender, Customer $customer, GeneralTenderChecklist $generalTenderChecklist, Product $product)
    {
        $customers = $customer->getAll();

        $generalTenderChecklists = $generalTenderChecklist->getAll();

        $products = $product->getProductNames();

        return view('tenders.edit', compact('tender', 'customers', 'generalTenderChecklists', 'products'));
    }

    public function update(Request $request, Tender $tender)
    {
        $tenderData = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|max:255',
            'status' => 'required|string|max:255',
            'participants' => 'required|integer',
            'published_on' => 'required|date',
            'closing_date' => 'required|date',
            'opening_date' => 'required|date',
            'customer_id' => 'nullable|integer',
            'description' => 'nullable|string',
            'tender' => 'required|array',
            'tender.*.product_id' => 'required|integer',
            'tender.*.quantity' => 'required|numeric',
            'tender.*.unit_price' => 'required|numeric',
            'tender.*.description' => 'nullable|string',
        ]);

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
