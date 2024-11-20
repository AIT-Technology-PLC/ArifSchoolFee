<?php

namespace App\Services\Models;

use App\Models\AcademicYear;
use App\Models\Route;
use App\Models\SchoolClass;
use App\Models\Section;
use App\Models\StudentCategory;
use App\Models\StudentGroup;
use App\Models\Vehicle;
use App\Models\Warehouse;

class StudentService
{
    public function create()
    {
        $currentStudentCode = nextReferenceNumber('students');
        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();
        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);
        $sections = Section::orderBy('name')->get(['id', 'name']);
        $categories = StudentCategory::orderBy('name')->get(['id', 'name']);
        $groups = StudentGroup::orderBy('name')->get(['id', 'name']);
        $academicYears = AcademicYear::orderBy('year')->get(['id', 'year']);
        $vehicles = Vehicle::orderBy('vehicle_number')->get(['id', 'vehicle_number']);
        $routes = Route::orderBy('title')->get(['id', 'title']);

        return compact(
            'currentStudentCode',
            'branches',
            'classes',
            'sections',
            'categories',
            'groups',
            'academicYears',
            'vehicles',
            'routes'
        );
    }

    public function edit($student)
    {
        $student->load(['warehouse', 'section', 'schoolClass', 'studentCategory', 'studentGroup']);

        $branches = Warehouse::withoutGlobalScopes([ActiveWarehouseScope::class])->get();
        $classes = SchoolClass::orderBy('name')->get(['id', 'name']);
        $sections = Section::orderBy('name')->get(['id', 'name']);
        $categories = StudentCategory::orderBy('name')->get(['id', 'name']);
        $groups = StudentGroup::orderBy('name')->get(['id', 'name']);
        $academicYears = AcademicYear::orderBy('year')->get(['id', 'year']);
        $vehicles = Vehicle::orderBy('vehicle_number')->get(['id', 'vehicle_number']);
        $routes = Route::orderBy('title')->get(['id', 'title']);

        return compact(
            'student',
            'branches',
            'classes',
            'sections',
            'categories',
            'groups',
            'academicYears',
            'vehicles',
            'routes'
        );
    }
}