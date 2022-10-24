<?php

namespace App\Services\Models;

use App\Actions\ApproveTransactionAction;
use App\Models\Price;
use Illuminate\Support\Facades\DB;

class PriceIncrementService
{
    public function approve($priceIncrement)
    {
        return DB::transaction(function () use ($priceIncrement) {
            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($priceIncrement);

            if (!$isExecuted) {
                DB::rollBack();
                return [$isExecuted, $message];
            }

            $prices = Price::whereIn('product_id', $priceIncrement->priceIncrementDetails->pluck('product_id'))->get();

            foreach ($prices as $price) {
                if ($priceIncrement->price_type == "amount" && $price->type == "fixed") {
                    $price->fixed_price = $price->fixed_price + $priceIncrement->price_increment;
                }

                if ($priceIncrement->price_type == "amount" && $price->type == "range") {
                    $price->min_price = $price->min_price + $priceIncrement->price_increment;
                    $price->max_price = $price->max_price + $priceIncrement->price_increment;
                }

                if ($priceIncrement->price_type == "percent" && $price->type == "fixed") {
                    $price->fixed_price = $price->fixed_price + (($price->fixed_price * $priceIncrement->price_increment) / 100);
                }

                if ($priceIncrement->price_type == "percent" && $price->type == "range") {
                    $price->min_price = $price->min_price + (($price->min_price * $priceIncrement->price_increment) / 100);
                    $price->max_price = $price->max_price + (($price->max_price * $priceIncrement->price_increment) / 100);
                }

                $price->save();
            }

            return [true, $message];
        });
    }
}
