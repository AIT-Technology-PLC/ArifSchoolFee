<?php

namespace App\Http\Controllers\Resource;

use App\DataTables\StudentDatatable;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreStudentRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Services\Models\StudentService;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\Student;
use App\Models\StudentCategory;
use App\Models\StudentGroup;
use App\Models\Warehouse;
use App\Services\Student\StudentOperationService;
use Illuminate\Support\Facades\DB;

class StudentController extends Controller
{
    private $studentService;

    public function __construct(StudentService $studentService)
    {
        $this->middleware('isFeatureAccessible:Student Management');

        $this->authorizeResource(Student::class, 'student');

        $this->studentService = $studentService;
    }

    public function index(StudentDatatable $datatable)
    {
        $datatable->builder()->setTableId('students-datatable')->orderBy(0, 'asc');

        $totalStudents = Student::count();

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();

        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);

        $sections = Section::orderBy('name')->get(['id', 'name']);

        $categories = StudentCategory::orderBy('name')->get(['id', 'name']);

        $groups = StudentGroup::orderBy('name')->get(['id', 'name']);

        return $datatable->render('students.index', compact('totalStudents', 'branches', 'classes', 'sections', 'categories', 'groups'));
    }

    public function create()
    {
        if (limitReached('student', Student::count())) {
            return back()->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'students']));
        }

        $data = $this->studentService->create();

        return view('students.create', $data);
    }

    public function store(StoreStudentRequest $request)
    {
        if (limitReached('student', Student::count())) {
            return back()->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'students']));
        }

        DB::transaction(function () use ($request) {
            $student = Student::firstOrCreate(
                $request->safe()->only(['first_name', 'father_name', 'last_name','school_class_id', 'section_id'] + ['company_id' => userCompany()->id]),
                $request->safe()->except(['first_name', 'father_name', 'last_name','school_class_id', 'section_id'] + ['company_id' => userCompany()->id]),
            );

            StudentOperationService::add($request, $student);

            return $student;
        });

        return redirect()->route('students.index')->with('successMessage', 'New Student Added Successfully.');
    }

    public function show(Student $student)
    {
        $student->load(['warehouse', 'section', 'schoolClass', 'studentCategory', 'studentGroup']);

        return view('students.show', compact('student'));
    }

    public function edit(Student $student)
    {
        $data = $this->studentService->edit($student);

        return view('students.edit', $data);
    }

    public function update(UpdateStudentRequest $request, Student $student)
    {
        if (limitReached('student', Student::count())) {
            return redirect()->route('students.index')->with('limitReachedMessage', __('messages.limit_reached', ['limit' => 'students']));
        }

        if ($student->hasUnpaidFees()) {
            return redirect()->route('students.index')->with('failedMessage', 'Cannot edit student details. Active unpaid fees are found.');
        }

        DB::transaction(function () use ($request, $student) {
            $student->update($request->validated());

            StudentOperationService::add($request, $student);

            return $student;
        });

        return redirect()->route('students.index')->with('successMessage', 'Updated Successfully.');
    }

    public function destroy(Student $student)
    {
        if ($student->hasUnpaidFees()) {
            return back()->with(['failedMessage' => 'Unable to delete student data. Active unpaid fees are found.']);
        }

        if ($student->feePayments()->exists() || $student->messageDetails()->exists()) {
            return back()->with(['failedMessage' => 'This Student data has already been used and cannot be deleted.']);
        }

        $student->delete();

        return back()->with('deleted', 'Deleted successfully.');
    }
}