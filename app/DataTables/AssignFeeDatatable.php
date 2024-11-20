<?php

namespace App\DataTables;

use App\Models\Student;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class AssignFeeDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('branch', fn($student) => ucfirst($student->warehouse->name))
            ->editColumn('class', fn($student) => ucfirst($student->schoolClass->name))
            ->editColumn('section', fn($student) => ucfirst($student->section->name))
            ->editColumn('gender', fn($student) => ucfirst($student->gender) )
            ->editColumn('category', fn($student) => $student->studentCategory->name)
            ->editColumn('checkbox', function ($student) {
                return view('components.datatables.add-mastser-checkbox-action', compact('student'));
            })
            ->addIndexColumn();
    }

    public function query(Student $student)
    {
        $query = $student->newQuery()->select('students.*');

        $query->when(is_numeric(request('branch')), fn($q) => $q->where('students.warehouse_id', request('branch')))
            ->when(is_numeric(request('class')), fn($q) => $q->where('students.school_class_id', request('class')))
            ->when(is_numeric(request('section')), fn($q) => $q->where('students.section_id', request('section')))
            ->when(is_numeric(request('category')), fn($q) => $q->where('students.student_category_id', request('category')))
            ->when(request('gender') == 'male', fn($query) => $query->where('gender','male'))
            ->when(request('gender') == 'female', fn($query) => $query->where('gender','female'));

        if (!request('branch') && !request('class') && !request('section') && !request('category') && !request('gender')) {
            $query->whereRaw('1 = 0');
        }

        return $query->with([
            'createdBy:id,name',
            'updatedBy:id,name',
            'warehouse:id,name',
            'schoolClass:id,name',
            'section:id,name',
            'studentCategory:id,name',
        ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('checkbox')->orderable(false)->exportable(false)->printable(false)->width(20)->addClass('dt-checkboxes'),
            Column::computed('#'),
            Column::make('first_name')->title('Name'),
            Column::make('father_name')->content('N/A'),
            Column::make('branch', 'warehouse.name'),
            Column::make('class', 'schoolClass.name'),
            Column::make('section', 'section.name'),
            Column::make('category', 'studentCategory.name'),
            Column::make('code')->title('Admission No'),
            Column::make('gender'),
        ];
    }

    protected function filename(): string
    {
        return 'Assign Fees_' . date('YmdHis');
    }
}
