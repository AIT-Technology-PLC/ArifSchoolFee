<?php

namespace App\Services\Models;

use App\Models\BillOfMaterialDetail;
use App\Models\Merchandise;
use App\Models\Product;
use App\Models\Warehouse;

class JobPlannerService
{
    public static function finalReport($details)
    {
        $finalReport = static::jobPlannerDetail($details);
        $finalReports = collect($finalReport);

        return $finalReports;
    }

    public static function jobPlannerDetail($details)
    {
        $merchandises = Merchandise::all();
        $billOfMaterialDetails = BillOfMaterialDetail::all();
        $productNames = Product::all();
        $factoryNames = Warehouse::all();
        $index = 0;

        foreach ($details as $detail) {
            $index += 1;
            $totalbom = $billOfMaterialDetails->where('bill_of_material_id', $detail['bill_of_material_id'])->values()->all();
            $productName = $productNames->where('id', $detail['product_id'])->first();
            $length = count($totalbom);
            $quantity = $detail['quantity'];
            $factoryName = $factoryNames->where('id', $detail['warehouse_id'])->first();

            for ($i = 0; $i < $length; $i++) {
                $rowMaterialName = $productNames->where('id', $totalbom[$i]->product_id)->first();
                $availableAmount = $merchandises->where('product_id', $totalbom[$i]->product_id)->where('warehouse_id', $detail['warehouse_id'])->first();
                $billOfMaterialDetail = $billOfMaterialDetails->where('bill_of_material_id', $detail['bill_of_material_id'])->where('product_id', $totalbom[$i]->product_id)->first();
                $requiredAmount = $billOfMaterialDetail->quantity * $detail['quantity'];
                $status = $availableAmount ? ($availableAmount->available >= $requiredAmount ? 'Sufficient' : 'Insufficient') : 'Insufficient';
                $productionCapacity = ($availableAmount && $billOfMaterialDetail) ? number_format($availableAmount->available / $billOfMaterialDetail->quantity, 2) : 0;
                $difference = ($availableAmount ? $availableAmount->available : 0) - $requiredAmount;
                $result[] = collect([
                    'index' => $index,
                    'factory_name' => $factoryName->name,
                    'product_name' => $productName->name,
                    'bill_of_material' => $billOfMaterialDetail->billOfMaterial->name,
                    'quantity' => $quantity,
                    'product_unit_of_measurement' => $productName->unit_of_measurement,
                    'raw_material' => $rowMaterialName->name,
                    'raw_material_unit_of_measurement' => $rowMaterialName->unit_of_measurement,
                    'required_amount' => $requiredAmount,
                    'available_amount' => $availableAmount ? $availableAmount->available : 0,
                    'difference' => $difference,
                    'production_capacity' => $productionCapacity,
                    'status' => $status,
                ]);
            };
        }

        return $result;
    }
}
