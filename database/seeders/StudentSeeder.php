<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Student;
use App\Models\StudentHistory;

class StudentSeeder extends Seeder
{
    public function run(): void
    {
        $students = Student::factory()->count(1)->create();

        foreach ($students as $student) {
            StudentHistory::firstOrCreate([
                'company_id' => $student->company_id,
                'student_id' => $student->id,
                'warehouse_id' => $student->warehouse_id,
                'school_class_id' => $student->school_class_id,
                'section_id' => $student->section_id,
                'academic_year_id' => $student->academic_year_id,
                'created_by' => $student->created_by,
                'updated_by' => $student->updated_by,
                'model_type' => 'App\Models\Student',
                'model_id' => $student->id,
            ]);
        }
    }
}
