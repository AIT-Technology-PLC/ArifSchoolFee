<?php

namespace App\Imports;

use App\Models\AttendanceDetail;
use App\Models\Employee;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class AttendanceImport implements ToModel, WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts
{
    use Importable;

    private $users;

    private $attendance;

    public function __construct($attendance)
    {
        $this->users = User::all();

        $this->attendance = $attendance;
    }

    public function model(array $row)
    {
        $employees = Employee::query()
            ->whereHas('user', function (Builder $query) use ($row) {
                $query->where('name', $row['employee_name']);
            })
            ->first();

        if (AttendanceDetail::where('employee_id', $employees->id)->exists()) {
            return null;
        }

        return new AttendanceDetail([
            'attendance_id' => $this->attendance->id,
            'employee_id' => $employees->id,
            'days' => $row['days'],
        ]);
    }

    public function rules(): array
    {
        return [
            'employee_name' => ['required', 'string', 'max:255', Rule::in($this->users->pluck('name'))],
            'days' => ['required', 'numeric', 'gt:0', function ($attribute, $value, $fail) {
                $difference = number_format((new Carbon($this->attendance->ending_period))->floatDiffInDays((new Carbon($this->attendance->starting_period))), 2, '.', '');
                if ($value > $difference) {
                    $fail('Absent days should not be greater than the period specified.');
                }
            }],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['employee_name'] = str()->squish($data['employee_name'] ?? '');

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
