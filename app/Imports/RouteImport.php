<?php

namespace App\Imports;

use App\Models\Route;
use App\Models\Vehicle;
use App\Rules\MustBelongToCompany;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;

class RouteImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $routes;

    private $vehicle;

    public function __construct()
    {
        $this->routes = Route::all();
    }

    public function onRow(Row $row)
    {
        if ($this->routes->where('name', $row['name'])->where('company_id', userCompany()->id)->count()) {
            return null;
        }

        $this->vehicle = Vehicle::firstWhere('name', $row['vehicle_number']);

        $route = Route::create([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'title' => $row['title'],
            'fare' => $row['fare'],
        ]);

        $route->vehicles()->sync($this->vehicle->id);
    }

    public function rules(): array
    {
        return [
            'title' => ['required', 'string', 'distinct'],
            'fare' => ['required', 'numeric', 'gte:0'],
            'vehicle_number' => ['required', 'integer', new MustBelongToCompany('vehicles', 'vehicle_number')],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['title'] = str()->squish($data['title'] ?? '');

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
