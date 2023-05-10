<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\ProductImport;
use App\Models\Product;
use App\Models\Warehouse;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Product Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('import', Product::class);

        ini_set('max_execution_time', '-1');

        (new ProductImport)->import($request->validated('file'));

        $rows = (new ProductImport)->toArray($request->validated('file'))[0];

        $warehouses = collect(array_keys($rows[0]))->filter(fn($item) => Warehouse::active()->where('name', str()->headline($item))->exists())->toArray();

        foreach ($rows as $row) {
            $data = [];

            $product = Product::firstWhere('name', str()->squish($row['product_name']));

            foreach ($warehouses as $warehouse) {
                $warehouse = Warehouse::firstWhere('name', str()->headline($warehouse));

                if (!is_numeric($row[str()->snake($warehouse->name)])) {
                    continue;
                }

                $data[] = [
                    'warehouse_id' => $warehouse->id,
                    'quantity' => $row[str()->snake($warehouse->name)],
                ];
            }

            $product->productReorders()->createMany($data);
        }

        return back()->with('imported', __('messages.file_imported'));
    }
}
