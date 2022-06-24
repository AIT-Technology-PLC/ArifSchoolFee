<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\JobDetail;

class JobDetailHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse, $product, $history;

    private function get()
    {
        $this->history = (new JobDetail())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($jobDetail) {
            return [
                'type' => 'JOB',
                'code' => $jobDetail->job->code,
                'date' => $jobDetail->job->issued_on,
                'quantity' => $jobDetail->is_bill_of_material ?
                number_format($jobDetail->wip + $jobDetail->available, 2) :
                number_format($jobDetail->available, 2),
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => ($jobDetail->is_bill_of_material ? 'Used in ' : 'Manufactured in ') . $this->warehouse->name,
                'function' => $jobDetail->is_bill_of_material ? 'subtract' : 'add',
            ];
        });
    }

    public function retrieve($warehouse, $product)
    {
        $this->product = $product;

        $this->warehouse = $warehouse;

        $this->get();

        $this->format();

        return $this->history;
    }
}
