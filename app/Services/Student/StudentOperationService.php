<?php

namespace App\Services\Student;

use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentHistory;
use Illuminate\Database\QueryException;

class StudentOperationService
{
    private static $properties = ['warehouse_id', 'academic_year_id', 'school_class_id', 'section_id'];

    public static function add($detail, $model)
    {
        $detail = static::formatData($detail);

        if (is_null($detail)) {
            return;
        }

        if (static::checkDuplicateHistory($detail, $model)) {
            return ;
        }

        static::createOrUpdateStudentHistory($detail,$model);
    }

    public static function createOrUpdateStudentHistory($detail, $model)
    {
        $historyData  = [
            'company_id' => userCompany()->id ?? $model->company_id,
            'student_id' => $model->id,
            'warehouse_id' => $detail['warehouse_id'] ?? $detail->warehouse_id,
            'academic_year_id' => $detail['academic_year_id'] ?? $detail->academic_year_id,
            'school_class_id' => $detail['school_class_id'] ?? $detail->school_class_id,
            'section_id' => $detail['section_id'] ?? $detail->section_id,
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'created_by' => authUser()->id,
            'updated_by' => authUser()->id,
        ];

        try {
            StudentHistory::updateOrCreate(
                [
                    'student_id' => $historyData['student_id'],
                    'school_class_id' => $historyData['school_class_id'],
                    'section_id' => $historyData['section_id'],
                    'academic_year_id' => $historyData['academic_year_id'],
                ],
                $historyData
            );
        } catch (QueryException $ex) {
            throw $ex;
        }
    }

    private static function checkDuplicateHistory($detail, $model)
    {
        $academicYear = AcademicYear::firstWhere('id', $detail['academic_year_id'] ?? $detail->academic_year_id)->id;
        $class = SchoolClass::firstWhere('id', $detail['school_class_id'] ?? $detail->school_class_id)->id;
        $section = Section::firstWhere('id', $detail['section_id'] ?? $detail->section_id)->id;

        return StudentHistory::where('student_id', $model->id)
            ->where('school_class_id', $class)
            ->where('section_id', $section)
            ->where('academic_year_id', $academicYear)
            ->exists();
    }

    private static function formatData($detail)
    {

        if ($detail instanceof \Illuminate\Http\Request) {
            $detail = $detail->all();
        }

        $missingFields = collect(static::$properties)->filter(fn($field) => !array_key_exists($field, $detail));
    
        if ($missingFields->isNotEmpty()) {
            return null;
        }

        return $detail;
    }
}
