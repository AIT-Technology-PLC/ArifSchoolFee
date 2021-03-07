<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\GeneralTenderChecklist;
use App\Models\Product;
use App\Models\Tender;
use App\Models\Warehouse;
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

    public function create(Customer $customer, GeneralTenderChecklist $generalTenderChecklist, Product $product, Warehouse $warehouse)
    {
        $customers = $customer->getAll();

        $generalTenderChecklists = $generalTenderChecklist->getAll();

        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('tenders.create', compact('customers', 'generalTenderChecklists', 'products', 'warehouses'));
    }

    public function store(Request $request)
    {
        $tenderData = $request->validate([

        ]);

        $tenderData['created_by'] = auth()->user()->id;
        $tenderData['updated_by'] = auth()->user()->id;

        $basicTenderData = Arr::except($tenderData, ['tender', 'checklist']);
        $tenderDetailsData = Arr::only($tenderData, 'tender');
        $tenderChecklistsData = Arr::only($tenderData, 'checklist');

        DB::transaction(function () use ($basicTenderData, $tenderDetailsData, $tenderChecklistsData) {
            $tender = $this->tender->create($basicTenderData);
            $tender->tenderDetails()->createMany($tenderDetailsData);
            $tender->tenderChecklists()->createMany($tenderChecklistsData);
        });

        return redirect()->route('tenders.index');
    }

    public function show(Tender $tender)
    {
        $tender->load(['customer', 'tenderDetails.product', 'tenderDetails.warehouse', 'tenderChecklists', 'company', 'createdBy', 'updatedBy']);

        return view('tenders.show', compact('tender'));
    }

    public function edit(Tender $tender, Customer $customer, GeneralTenderChecklist $generalTenderChecklist, Product $product, Warehouse $warehouse)
    {
        $customers = $customer->getAll();

        $generalTenderChecklists = $generalTenderChecklist->getAll();

        $products = $product->getProductNames();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('tenders.create', compact('tender', 'customers', 'generalTenderChecklists', 'products', 'warehouses'));
    }

    public function update(Request $request, Tender $tender)
    {
        $tenderData = $request->validate([

        ]);

        $tenderData['updated_by'] = auth()->user()->id;

        $basicTenderData = Arr::except($tenderData, ['tender', 'checklist']);
        $tenderDetailsData = Arr::only($tenderData, 'tender');
        $tenderChecklistsData = Arr::only($tenderData, 'checklist');

        DB::transaction(function () use ($tender, $basicTenderData, $tenderDetailsData, $tenderChecklistsData) {
            $tender = $tender->create($basicTenderData);

            for ($i = 0; $i < count($tenderDetailsData); $i++) {
                $tender->tenderDetails[$i]->update($tenderDetailsData[$i]);
            }

            for ($i = 0; $i < count($tenderChecklistsData); $i++) {
                $tender->tenderChecklists[$i]->update($tenderChecklistsData[$i]);
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
