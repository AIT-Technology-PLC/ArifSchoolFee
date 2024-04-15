<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SaleFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => nextReferenceNumber('sales'),
            'issued_on' => now(),
            'payment_type' => 'Cash Payment',
            'cash_received_type' => 'percent',
            'cash_received' => 100,
        ];
    }
}
