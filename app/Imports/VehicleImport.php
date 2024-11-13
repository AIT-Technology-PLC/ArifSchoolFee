<?php

namespace App\Imports;

use App\Models\Vehicle;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class VehicleImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $vehicles;

    public function __construct()
    {
        $this->vehicles = Vehicle::all();
    }

    public function onRow(Row $row)
    {
        if ($this->vehicles->where('name', $row['vehicle_number'])->where('company_id', userCompany()->id)->count()) {
            return null;
        }

        return Vehicle::create([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'vehicle_number' => $row['vehicle_number'],
            'vehicle_model' => $row['vehicle_model'],
            'year_made' => $row['year_made'] ?? null,
            'driver_phone' => $row['driver_phone'],
            'note' => $row['note'] ?? null,
        ]);
    }

    public function rules(): array
    {
        return [
            'vehicle_number' => ['required', 'string', 'max:20'],
            'vehicle_model' => ['required', 'string', 'max:25'],
            'year_made' => ['nullable', 'integer','gte:0' ,'lte:' . date('Y')],
            'driver_name' => ['required', 'string','max:30'],
            'driver_phone' => ['required', 'string','max:15', 'unique:vehicles',],
            'note' => ['nullable', 'string', 'max:100'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['vehicle_number'] = str()->squish($data['vehicle_number'] ?? '');

        return $data;
    }

    public function chunkSize(): int
    {
        return 50;
    }

    public function batchSize(): int
    {
        return 50;
    }
}
