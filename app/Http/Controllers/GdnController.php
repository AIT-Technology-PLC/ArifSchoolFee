<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Gdn;
use App\Models\Product;
use App\Models\Sale;
use App\Models\Warehouse;
use App\Services\StoreSaleableProducts;
use App\Traits\PrependCompanyId;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class GdnController extends Controller
{
    use PrependCompanyId;

    private $gdn;

    public function __construct(Gdn $gdn)
    {
        $this->gdn = $gdn;
    }

    public function index(Gdn $gdn)
    {
        $gdns = $gdn->getAll()->load(['createdBy', 'updatedBy', 'sale', 'customer']);

        $totalGdns = $gdn->countGdnsOfCompany();

        return view('gdns.index', compact('gdns', 'totalGdns'));
    }

    public function create(Product $product, Customer $customer, Sale $sale, Warehouse $warehouse)
    {
        $products = $product->getProductNames();

        $customers = $customer->getCustomerNames();

        $sales = $sale->getManualSales();

        $warehouses = $warehouse->getAllWithoutRelations();

        return view('gdns.create', compact('products', 'customers', 'sales', 'warehouses'));
    }

    public function store(Request $request)
    {
        $request['code'] = $this->prependCompanyId($request->code);

        $gdnData = $request->validate([
            'code' => 'required|string|unique:gdns',
            'gdn' => 'required|array',
            'gdn.*.product_id' => 'required|integer',
            'gdn.*.warehouse_id' => 'required|integer',
            'gdn.*.quantity' => 'required|numeric|min:1',
            'gdn.*.desciption' => 'nullable|string',
            'customer_id' => 'nullable|integer',
            'sale_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'status' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $gdnData['company_id'] = auth()->user()->employee->company_id;
        $gdnData['created_by'] = auth()->user()->id;
        $gdnData['updated_by'] = auth()->user()->id;

        $basicGdnData = Arr::except($gdnData, 'gdn');
        $gdnDetailsData = $gdnData['gdn'];

        $isGdnValid = DB::transaction(function () use ($basicGdnData, $gdnDetailsData) {
            $gdn = $this->gdn->create($basicGdnData);
            $gdn->gdnDetails()->createMany($gdnDetailsData);
            $isGdnValid = StoreSaleableProducts::storeSoldProducts($gdn);

            if (!$isGdnValid) {
                DB::rollback();
            }

            return $isGdnValid;
        });

        return $isGdnValid ?
        redirect()->route('gdns.index') :
        redirect()->back()->withInput($request->all());
    }

    public function show(Gdn $gdn)
    {
        $gdn->load(['gdnDetails.product', 'gdnDetails.warehouse', 'customer', 'sale']);

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

    public function update(Request $request, Gdn $gdn)
    {
        if ($gdn->isGdnSubtracted()) {
            return redirect()->route('gdns.show', $gdn->id);
        }

        $request['code'] = $this->prependCompanyId($request->code);

        $gdnData = $request->validate([
            'code' => 'required|string|unique:gdns,code,' . $gdn->id,
            'gdn' => 'required|array',
            'gdn.*.product_id' => 'required|integer',
            'gdn.*.warehouse_id' => 'required|integer',
            'gdn.*.quantity' => 'required|numeric|min:1',
            'gdn.*.desciption' => 'nullable|string',
            'customer_id' => 'nullable|integer',
            'sale_id' => 'nullable|integer',
            'issued_on' => 'required|date',
            'description' => 'nullable|string',
        ]);

        $gdnData['updated_by'] = auth()->user()->id;

        $basicGdnData = Arr::except($gdnData, 'gdn');
        $gdnDetailsData = $gdnData['gdn'];

        DB::transaction(function () use ($basicGdnData, $gdnDetailsData, $gdn) {
            $gdn->update($basicGdnData);

            for ($i = 0; $i < count($gdnDetailsData); $i++) {
                $gdn->gdnDetails[$i]->update($gdnDetailsData[$i]);
            }
        });

        return redirect()->route('gdns.show', $gdn->id);
    }

    public function destroy(Gdn $gdn)
    {
        //
    }
}
