<?php

namespace App\Imports;

use App\Models\ChassisNumber;
use App\Models\JobDetail;
use App\Models\Product;
use App\Rules\MustBelongToCompany;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class ChassisImport implements WithHeadingRow, ToModel, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $products;

    private $job;

    private $jobDetail;

    private $chassisNumbers;

    public function __construct($job)
    {
        $this->job = $job;

        $this->jobDetail = JobDetail::where('job_order_id', $this->job->id)->get();

        $this->products = Product::all(['id', 'name']);

        $this->chassisNumbers = ChassisNumber::all();
    }

    public function model(array $row)
    {
        foreach ($this->jobDetail as $jobDetail) {
            $productID = $this->products->firstWhere('name', $row['product_name'])->id;

            $available = $jobDetail->available;
            $chassis = ChassisNumber::where('job_detail_id', $jobDetail->id)->count();

            if ($available <= $chassis) {
                continue;
            }

            if ($jobDetail->product_id == $productID) {
                ChassisNumber::create([
                    'job_detail_id' => $jobDetail->id,
                    'product_id' => $productID,
                    'warehouse_id' => $this->job->factory_id,
                    'chassis_number' => $row['chassis_number'],
                    'engine_number' => $row['engine_number'],
                ]);
            }
        }
    }

    public function rules(): array
    {
        return [
            'product_name' => ['required', 'string', 'max:255', new MustBelongToCompany('products', 'name')],
            'chassis_number' => ['required', 'string', 'max:255', 'unique:chassis_numbers'],
            'engine_number' => ['required', 'string', 'max:255', 'unique:chassis_numbers'],
        ];
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function batchSize(): int
    {
        return 500;
    }
}
