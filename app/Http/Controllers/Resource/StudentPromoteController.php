<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\StudentPromoteDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentPromoteRequest;
use App\Models\AcademicYear;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentPromote;
use App\Models\Warehouse;
use App\Services\Student\StudentOperationService;
use Illuminate\Support\Facades\DB;

class StudentPromoteController extends Controller
{
    public function __construct()
    {
        $this->middleware('isFeatureAccessible:Student Promote');

        $this->authorizeResource(StudentPromote::class);
    }

    public function index(StudentPromoteDatatable $datatable)
    {
        $datatable->builder()->setTableId('student-promotes-datatable')->orderBy(0, 'asc');

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $academicYears = AcademicYear::orderBy('year')->get(['id', 'year']);

        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);

        $sections = Section::orderBy('name')->get(['id', 'name']);

        return $datatable->render('student-promotes.index', compact('branches', 'academicYears', 'classes', 'sections'));
    }

    public function store(StoreStudentPromoteRequest $request)
    {
        DB::transaction(function () use ($request) {
            foreach ($request->student_id as $studentId) {
                $student = Student::findOrFail($studentId);

                if ($student->academicYear->id == $request->academic_year_id) {
                    return;
                }

                $student->update([
                    'warehouse_id' => $request->warehouse_id,
                    'academic_year_id' => $request->academic_year_id,
                    'school_class_id' => $request->school_class_id,
                    'section_id' => $request->section_id,
                ]);
    
                StudentOperationService::add($request, $student);
            }
        });

        return redirect()->back()->with('successMessage', 'Students successfully promoted.');
    }
}
