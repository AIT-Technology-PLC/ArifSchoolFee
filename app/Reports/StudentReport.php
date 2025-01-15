<?php

namespace App\Reports;

use App\Models\Student;
use App\Scopes\BranchScope;
use Illuminate\Support\Carbon;

class StudentReport
{
    private $query;

    private $filters;

    public function __construct($filters)
    {
        $this->filters = $filters;

        $this->setQuery();
    }

    public function __get($name)
    {
        if (!isset($this->$name)) {
            $this->$name = $this->$name();
        }

        return $this->$name;
    }

    private function setQuery()
    {
        $this->query = Student::query()
            ->join('warehouses', 'students.warehouse_id', '=', 'warehouses.id')
            ->join('school_classes', 'students.school_class_id', '=', 'school_classes.id')
            ->join('sections', 'students.section_id', '=', 'sections.id')
            ->join('academic_years', 'students.academic_year_id', '=', 'academic_years.id')
            ->join('routes', 'students.route_id', '=', 'routes.id')
            ->join('vehicles', 'students.vehicle_id', '=', 'vehicles.id')
            ->leftJoin('users', 'students.created_by', '=', 'users.id')
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('students.warehouse_id', $this->filters['branches']))
            ->when(isset($this->filters['sections']), fn($query) => $query->where('students.section_id', $this->filters['sections']))
            ->when(isset($this->filters['classes']), fn($query) => $query->where('students.school_class_id', $this->filters['classes']))
            ->when(isset($this->filters['academicYears']), fn($query) => $query->where('students.academic_year_id', $this->filters['academicYears']));
    }

    // Return full list of students
    public function getStudentList()
    {
        $students = (clone $this->query)
        ->select('students.code','students.current_address','students.permanent_address', 'students.first_name', 'students.father_name', 'students.last_name', 'students.gender', 'students.phone' , 'warehouses.name AS warehouse_name', 'school_classes.name AS school_class_name', 'sections.name AS section_name', 'routes.title AS route_name', 'vehicles.vehicle_number AS vehicle_number', 'academic_years.year AS academic_year_name')
        ->get();

        return $students;
    }

    // Total students
    public function getTotalStudents()
    {
        return (clone $this->query)->count();
    }

    // Students by gender
    public function getStudentsByGender()
    {
        return (clone $this->query)
            ->selectRaw('COUNT(*) AS total, students.gender')
            ->groupBy('students.gender')
            ->get();
    }

    // Students by branch distribution
    public function getStudentsByBranch()
    {
        return (clone $this->query)
            ->selectRaw('COUNT(*) AS total, warehouses.name AS branch_name')
            ->groupBy('branch_name')
            ->orderByDesc('total')
            ->get();
    }

    // Students that use a route or not
    public function getStudentsByRouteUsage()
    {
        $data = (clone $this->query)
            ->selectRaw('COUNT(*) AS total, CASE 
                            WHEN students.route_id IS NOT NULL AND students.vehicle_id IS NOT NULL THEN "Yes"
                            ELSE "No"
                        END AS route_usage')
            ->groupBy('route_usage')
            ->get();

        // Process the data into a structured format
        $result = [
            'uses_route' => $data->firstWhere('route_usage', 'Yes')->total ?? 0,
            'does_not_use_route' => $data->firstWhere('route_usage', 'No')->total ?? 0,
        ];

        return $result;
    }
}
