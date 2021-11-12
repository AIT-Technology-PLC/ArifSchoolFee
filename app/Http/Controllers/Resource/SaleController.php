<?php

namespace App\Http\Controllers\Resource;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreSaleRequest;
use App\Http\Requests\UpdateSaleRequest;
use App\Models\Customer;
use App\Models\Sale;
use App\Services\NextReferenceNumService;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Sale Management');

        $this->authorizeResource(Sale::class, 'sale');
    }

    public function index()
    {
        $sales = Sale::with(['createdBy', 'updatedBy', 'saleDetails'])->latest('code')->get();

        $totalSales = Sale::count();

        return view('sales.index', compact('sales', 'totalSales'));
    }

    public function create()
    {
        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        $currentReceiptNo = NextReferenceNumService::table('sales');

        return view('sales.create', compact('customers', 'currentReceiptNo'));
    }

    public function store(StoreSaleRequest $request)
    {
        $sale = DB::transaction(function () use ($request) {
            $sale = Sale::create($request->except('sale'));

            $sale->saleDetails()->createMany($request->sale);

            return $sale;
        });

        return redirect()->route('sales.show', $sale->id);
    }

    public function show(Sale $sale)
    {
        $sale->load(['saleDetails.product', 'gdns', 'customer']);

        return view('sales.show', compact('sale'));
    }

    public function edit(Sale $sale)
    {
        $sale->load('saleDetails.product');

        $customers = Customer::orderBy('company_name')->get(['id', 'company_name']);

        return view('sales.edit', compact('sale', 'customers'));
    }

    public function update(UpdateSaleRequest $request, Sale $sale)
    {
        DB::transaction(function () use ($request, $sale) {
            $sale->update($request->except('sale'));

            for ($i = 0; $i < count($request->sale); $i++) {
                $sale->saleDetails[$i]->update($request->sale[$i]);
            }
        });

        return redirect()->route('sales.show', $sale->id);
    }

    public function destroy(Sale $sale)
    {
        $sale->forceDelete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}
