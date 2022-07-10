<?php

namespace App\Services\InventoryHistory;

use App\Interfaces\DetailHistoryServiceInterface;
use App\Models\JobExtra;

class JobExtraHistoryService implements DetailHistoryServiceInterface
{
    private $warehouse;

    private $product;

    private $history;

    private function get()
    {
        $this->history = (new JobExtra())
            ->getByWarehouseAndProduct(
                $this->warehouse,
                $this->product
            );
    }

    private function format()
    {
        $this->history->transform(function ($jobExtra) {
            return [
                'type' => 'JOB',
                'url' => '/jobs/'.$jobExtra->job_order_id,
                'code' => $jobExtra->job->code,
                'date' => $jobExtra->job->issued_on,
                'quantity' => $jobExtra->quantity,
                'balance' => 0.00,
                'unit_of_measurement' => $this->product->unit_of_measurement,
                'details' => ($jobExtra->status == 'subtracted' ? 'Subtracted' : 'Added').' in '.$this->warehouse->name,
                'function' => $jobExtra->status == 'subtracted' ? 'subtract' : 'add',
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
