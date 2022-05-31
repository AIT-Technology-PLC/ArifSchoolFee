<?php

namespace App\Interfaces;

use App\Models\Sale;

interface CashRegisterInterface
{
    public function create(Sale $sale);

    public function cancel($saleCode);

    public function getStatus($saleCode);
}
