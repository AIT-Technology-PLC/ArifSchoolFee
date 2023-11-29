<?php

namespace App\Observers;

use App\Models\Product;
use App\Utilities\Notifiables;
use App\Notifications\ProductPriceUpdated;
use Illuminate\Support\Facades\Notification;

class ProductPriceObserver
{
    public function updating(Product $product)
    {
        if (!isFeatureEnabled('Inventory Valuation') || !isset($product->profit_margin_amount) || !isset($product->profit_margin_type)) {
            return;
        }

        if ($product->isDirty($product->inventory_valuation_method . '_unit_cost')) {
            $newPrice = $this->calculateNewPrice($product->unitCost, $product->profit_margin_type, $product->profit_margin_amount);

            $price = $product->prices()->create([
                'fixed_price' => $newPrice,
                'is_active' => 0,
            ]);

            Notification::send(Notifiables::byPermission('Read Price'), new ProductPriceUpdated($price));
        }
    }

    private function calculateNewPrice($cost, $marginType, $marginAmount)
    {
        if ($marginType === 'percent') {
            $profitMargin = $cost * ($marginAmount / 100);
            $newPrice = $cost + $profitMargin;
        }

        if ($marginType === 'amount') {
            $newPrice = $cost + $marginAmount;
        }

        return $newPrice;
    }
}
