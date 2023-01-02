<?php

namespace App\Http\Controllers\Action;

use App\Http\Controllers\Controller;
use App\Http\Requests\UploadImportFileRequest;
use App\Imports\EmployeeCompensationImport;
use App\Models\Compensation;
use App\Models\Employee;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class EmployeeCompensationController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:User Management');
        $this->middleware('isFeatureAccessible:Compensation Management');
    }

    public function import(UploadImportFileRequest $request)
    {
        $this->authorize('create', Employee::class);

        ini_set('max_execution_time', '-1');

        $rows = (new EmployeeCompensationImport)->toArray($request->validated('file'))[0];

        $compensations = collect(array_keys($rows[0]))->filter(fn($item) => Compensation::active()->canBeInputtedManually()->where('name', str()->headline($item))->exists())->toArray();

        DB::transaction(function () use ($rows, $compensations) {
            foreach ($rows as $row) {
                $data = [];

                $employee = User::where('name', str()->squish($row['employee_name']))->first()?->employee;

                if ($employee && $employee->employeeCompensations()->doesntExist()) {
                    foreach ($compensations as $compensation) {
                        $compensation = Compensation::firstWhere('name', str()->headline($compensation));

                        $data[] = [
                            'compensation_id' => $compensation->id,
                            'amount' => $compensation->maximum_amount >= $row[str()->snake($compensation->name)]
                            ? $row[str()->snake($compensation->name)]
                            : $compensation->maximum_amount,
                        ];
                    }

                    $employee->employeeCompensations()->createMany($data);
                }
            }
        });

        return back()->with('imported', __('messages.file_imported'));
    }
}
