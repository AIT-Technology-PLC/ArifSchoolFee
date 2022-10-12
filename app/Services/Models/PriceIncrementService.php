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

            $priceList = Price::whereIn('product_id', $priceIncrement->priceIncrementDetails->pluck('product_id'))->get();

            foreach ($priceList as $price) {
                $intialPrice = $price->fixed_price;
                $minInitialPrice = $price->min_price;
                $maxInitialPrice = $price->max_price;

                if ($priceIncrement->price_type == "amount" && $price->type == "fixed") {
                    $price->fixed_price = $intialPrice + $priceIncrement->price_increment;
                }

                if ($priceIncrement->price_type == "amount" && $price->type == "range") {
                    $price->min_price = $minInitialPrice + $priceIncrement->price_increment;
                    $price->max_price = $maxInitialPrice + $priceIncrement->price_increment;
                }

                if ($priceIncrement->price_type == "percent" && $price->type == "fixed") {
                    $price->fixed_price = $intialPrice + (($intialPrice * $priceIncrement->price_increment) / 100);
                }

                if ($priceIncrement->price_type == "percent" && $price->type == "range") {
                    $price->min_price = $minInitialPrice + (($minInitialPrice * $priceIncrement->price_increment) / 100);
                    $price->max_price = $maxInitialPrice + (($maxInitialPrice * $priceIncrement->price_increment) / 100);
                }

                $price->save();
            }

            [$isExecuted, $message] = (new ApproveTransactionAction)->execute($priceIncrement);

            return [$isExecuted, $message];
        });
    }
}