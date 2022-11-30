<?php

namespace App\Imports;

use App\Models\Compensation;
use App\Models\Employee;
use App\Models\EmployeeCompensation;
use App\Models\User;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class EmployeeCompensationImport implements WithHeadingRow, ToModel, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $users;

    private $compensations;

    private $employeeCompensations;

    public function __construct()
    {
        $this->users = User::with('employee')->get();

        $this->compensations = Compensation::all();

        $this->employeeCompensations = EmployeeCompensation::all();
    }

    public function model(array $row)
    {
        $user = $this->users->firstWhere('name', $row['employee_name']);

        $compensation = $this->compensations->firstWhere('name', $row['compensation_name']);

        if ($this->employeeCompensations->where('employee_id', $user->employee->id)->where('compensation_id', $compensation->id)->count()) {
            return null;
        }

        $employeeCompensation = new EmployeeCompensation([
            'employee_id' => $user->employee->id,
            'compensation_id' => $compensation->id,
            'amount' => $row['amount'],
        ]);

        $this->employeeCompensations->push($employeeCompensation);

        return $employeeCompensation;
    }

    public function rules(): array
    {
        return [
            'employee_name' => ['required', 'string', 'max:255', Rule::in($this->users->pluck('name'))],
            'compensation_name' => ['required', 'string', 'max:255', Rule::when(!isFeatureEnabled('Compensation Management'), 'prohibited'), Rule::in(Compensation::active()->canBeInputtedManually()->pluck('name'))],
            'amount' => ['required', 'numeric', 'min:0', Rule::when(!isFeatureEnabled('Compensation Management'), 'prohibited')],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['employee_name'] = str()->squish($data['employee_name'] ?? '');
        $data['compensation_name'] = str()->squish($data['compensation_name'] ?? '');
        $data['amount'] = $data['amount'] > $this->compensations->firstWhere('name', $data['compensation_name'])->maximum_amount ? null : $data['amount'];

        return $data;
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
