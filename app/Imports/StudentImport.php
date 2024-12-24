<?php

namespace App\Imports;

use App\Models\Route;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentCategory;
use App\Models\StudentGroup;
use App\Models\Vehicle;
use App\Models\Warehouse;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Row;
use Maatwebsite\Excel\Concerns\OnEachRow;
use App\Rules\MustBelongToCompany;
use Illuminate\Validation\Rule;
use App\Services\Student\StudentOperationService;

class StudentImport implements WithHeadingRow, WithValidation, WithChunkReading, WithBatchInserts, OnEachRow
{
    use Importable;

    private $students;

    private $warehouses;

    private $classes;

    private $sections;

    public function __construct()
    {
        $this->students = Student::all();

        $this->warehouses = Warehouse::all(['id', 'name']);

        $this->classes = SchoolClass::all(['id', 'name']);

        $this->sections = Section::all(['id', 'name']);
    }

    public function onRow(Row $row)
    {
        if ($this->students->where('first_name', $row['first_name'])
                        ->where('father_name', $row['father_name'])
                        ->where('last_name', $row['last_name'])
                        ->where('school_class_id', $this->classes->where('name', $row['class']))
                        ->where('section_id', $this->sections->where('name', $row['section']))
                        ->count())
        {
            return null;
        }

        $student = Student::create([
            'company_id' => userCompany()->id,
            'code' => $this->students->isEmpty() ? nextReferenceNumber('students') : ($this->students->last()->code + 1),
            'warehouse_id' => Warehouse::firstWhere('name', $row['branch_name'])->id,
            'school_class_id' => SchoolClass::firstWhere('name', $row['class'])->id,
            'section_id' => Section::firstWhere('name', $row['section'])->id,
            'student_category_id_id' => StudentCategory::firstWhere('name', $row['student_category'])->id,
            'student_group_id' => StudentGroup::firstWhere('name', $row['student_group'])->id,
            'academic_year_id' => Vehicle::firstWhere('name', $row['academic_year'])->id,
            'route_id' => Route::firstWhere('name', $row['route'])->id ?? null,
            'vehicle_id' => Vehicle::firstWhere('name', $row['vehicle_number'])->id ?? null,
            'first_name' => $row['first_name'],
            'last_name' => $row['last_name'] ?? null,
            'gender' => $row['gender'],
            'email' => $row['email'],
            'phone' => $row['phone'],
            'date_of_birth' => $row['date_of_birth'] ?? null,
            'admission_date' => $row['admission_date'] ?? null,
            'father_name' => $row['father_name'] ?? null,
            'father_phone' => $row['father_phone'] ?? null,
            'mother_name' => $row['mother_name'] ?? null,
            'mother_phone' => $row['mother_phone'] ?? null,
            'current_address' => $row['current_address'] ?? null,
            'permanent_address' => $row['permanent_address'] ?? null,
        ]);

        StudentOperationService::add($row, $student);

        return $student;
    }

    public function rules(): array
    {
        return [
            'branch_name' => ['required', 'integer', new MustBelongToCompany('warehouses', 'name')],
            'class' => ['required', 'integer', new MustBelongToCompany('school_classes','name')],
            'section' => ['required', 'integer', new MustBelongToCompany('sections', 'name')],
            'student_category' => ['required', 'integer', new MustBelongToCompany('student_categories', 'name')],
            'student_group' => ['required', 'integer', new MustBelongToCompany('student_groups','name')],
            'academic_year' => ['required', 'integer', new MustBelongToCompany('academic_years','year')],
            'route' => ['nullable', 'integer', new MustBelongToCompany('routes', 'title')],
            'vehicle_number' => ['nullable', 'integer', new MustBelongToCompany('vehicles')],
            'first_name' => ['required', 'string', 'max:15'],
            'last_name' => ['nullable', 'string', 'max:15'],
            'gender' => ['required', 'string', 'max:6', Rule::in(['male', 'female'])],
            'email' => ['required', 'string', 'email', 'max:30', 'unique:students'],
            'phone' => ['required', 'string', 'max:15', 'unique:students'],
            'date_of_birth' => ['nullable', 'date', 'before:' . now()],
            'admission_date' => ['nullable', 'date'],
            'father_name' => ['nullable', 'string', 'max:15', 'required_unless:father_phone,null'],
            'father_phone' => ['nullable', 'string', 'max:15', 'required_unless:father_name,null'],
            'mother_name' => ['nullable', 'string', 'max:15', 'required_unless:mother_phone,null'],
            'mother_phone' => ['nullable', 'string', 'max:15', 'required_unless:mother_name,null'],
            'current_address' => ['nullable', 'string', 'max:50'],
            'permanent_address' => ['nullable', 'string', 'max:50'],
        ];
    }

    public function prepareForValidation($data, $index)
    {
        $data['branch_name'] = str()->squish($data['branch_name'] ?? '');
        $data['class'] = str()->squish($data['class'] ?? '');
        $data['section'] = str()->squish($data['section'] ?? '');
        $data['first_name'] = str()->squish($data['first_name'] ?? '');
        $data['last_name'] = str()->squish($data['last_name'] ?? '');
        $data['father_name'] = str()->squish($data['father_name'] ?? '');
        $data['mother_name'] = str()->squish($data['mother_name'] ?? '');

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
