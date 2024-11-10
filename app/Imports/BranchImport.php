<?php

namespace App\Imports;

use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class BranchImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $branches;

    private $activeBranches;

    public function __construct()
    {
        $this->branches = Warehouse::all();

        $this->activeBranches = Warehouse::active()->get();
    }

    public function model(array $row)
    {
        if ($this->branches->where('name', $row['branch_name'])->count()) {
            return null;
        }

        if (limitReached('branch', $this->activeBranches->count())) {
            session('limitReachedMessage', __('messages.limit_reached', ['limit' => 'branches']));

            return null;
        }

        $branche = new Warehouse([
            'company_id' => userCompany()->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
            'name' => $row['branch_name'],
            'location' => $row['branch_location'],
            'is_active' => '1',
            'email' => $row['branch_email'] ?? '',
            'phone' => $row['branch_phone'] ?? '',
        ]);

        $this->activeBranches->push($branche);

        $this->branches->push($branche);

        return $branche;
    }

    public function rules(): array
    {
        return [
            'branch_name' => ['required', 'string', 'max:25'],
            'branch_location' => ['required', 'string', 'max:50'],
            'branch_email' => ['nullable', 'string', 'email'],
            'branch_phone' => ['nullable', 'string'],
        ];
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
