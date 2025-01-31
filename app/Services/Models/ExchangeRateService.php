<?php

namespace App\Services\Models;

use Illuminate\Support\Facades\Http;
use App\Models\Currency;

class ExchangeRateService
{
    public function updateExchangeRates()
    {
        // Example: Using ExchangeRate-API
        $response = Http::get('https://api.exchangerate-api.com/v4/latest/USD'); // Replace with your API endpoint

        if ($response->successful()) {
            $rates = $response->json()['rates'];

            foreach ($rates as $currencyCode => $rate) {
                $currency = Currency::where('code', $currencyCode)->first();
                if ($currency) {
                    $currency->update([
                        'exchange_rate' => $rate,
                        'rate_source' => 'api',
                    ]);
                }
            }
        }
    }
}
