<?php

namespace App\Reports;

use App\Models\Staff;

class StaffReport
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
        $this->query = Staff::query()
            ->join('warehouses', 'staff.warehouse_id', '=', 'warehouses.id')
            ->join('designations', 'staff.designation_id', '=', 'designations.id')
            ->join('departments', 'staff.department_id', '=', 'departments.id')
            ->leftJoin('users', 'staff.created_by', '=', 'users.id')
            ->when(isset($this->filters['branches']), fn($q) => $q->whereIn('staff.warehouse_id', $this->filters['branches']))
            ->when(isset($this->filters['designations']), fn($query) => $query->where('staff.designation_id', $this->filters['designations']))
            ->when(isset($this->filters['departments']), fn($query) => $query->where('staff.department_id', $this->filters['departments']));
    }

    // Return full list of staff
    public function getStaffList()
    {
        $staff = (clone $this->query)
        ->select('staff.code', 'staff.marital_status', 'staff.current_address','staff.permanent_address', 'staff.first_name', 'staff.father_name', 'staff.last_name', 'staff.gender', 'staff.phone', 'staff.email' , 'warehouses.name AS warehouse_name', 'designations.name AS designation_name', 'departments.name AS department_name')
        ->get();

        return $staff;
    }

    // Total staff
    public function getTotalStaff()
    {
        return (clone $this->query)->count();
    }

    // Staff count by gender
    public function getStaffByGenderUsage()
    {
        $data = (clone $this->query)
            ->selectRaw('COUNT(*) AS total, CASE 
                            WHEN STAFF.gender = "male" THEN "Male"
                            WHEN STAFF.gender = "female" THEN "Female"
                            ELSE "Other"
                        END AS gender_category')
            ->groupBy('gender_category')
            ->get();

        // Process the data into a structured format
        $result = [
            'male' => $data->firstWhere('gender_category', 'Male')->total ?? 0,
            'female' => $data->firstWhere('gender_category', 'Female')->total ?? 0,
            'other' => $data->firstWhere('gender_category', 'Other')->total ?? 0,
        ];

        return $result;
    }

    // Staff by Marital Status
    public function getStaffByMaritalStatus()
    {
        return (clone $this->query)
            ->selectRaw('COUNT(*) AS total, staff.marital_status')
            ->groupBy('staff.marital_status')
            ->get();
    }

    // Staff by branch distribution
    public function getStaffByBranch()
    {
        return (clone $this->query)
            ->selectRaw('COUNT(*) AS total, warehouses.name AS branch_name')
            ->groupBy('branch_name')
            ->orderByDesc('total')
            ->get();
    }

    // Staff by designation 
    public function getStaffByDesignation()
    {
        return (clone $this->query)
            ->selectRaw('COUNT(*) AS total, designations.name AS designation_name')
            ->groupBy('designation_name')
            ->orderByDesc('total')
            ->get();
    }

    // Staff by department 
    public function getStaffByDepartment()
    {
        return (clone $this->query)
            ->selectRaw('COUNT(*) AS total, departments.name AS department_name')
            ->groupBy('department_name')
            ->orderByDesc('total')
            ->get();
    }
}