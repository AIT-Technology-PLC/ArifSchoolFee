<?php

namespace App\Actions;

use App\Models\Product;
use App\Models\Warehouse;

class ProductReorderImportAction
{
    public function execute($rows)
    {
        $warehouses = collect(array_keys($rows[0]))->filter(fn($item) => Warehouse::active()->where('name', str()->headline($item))->exists())->toArray();

        if (empty($warehouses)) {
            return;
        }

        foreach ($rows as $row) {
            $data = [];

            $product = Product::firstWhere('name', $row['product_name']);

            if (is_null($product)) {
                continue;
            }

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
    }
}
