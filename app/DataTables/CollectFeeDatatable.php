<?php

namespace App\DataTables;

use App\Models\Student;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class CollectFeeDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('branch', fn($student) => $student->warehouse->name)
            ->editColumn('class', fn($student) => str($student->schoolClass->name)->append(' ( '.$student->section->name . ' )'))
            ->editColumn('actions', function ($student) {
                return view('components.datatables.collect-fee-action', compact('student'));
            })
            ->addIndexColumn();
    }

    public function query(Student $student)
    {
        $query = $student->newQuery()->select('students.*');

        $query->whereHas('assignFeeMasters');

        $query->when(is_numeric(request('branch')), fn($q) => $q->where('students.warehouse_id', request('branch')))
            ->when(is_numeric(request('class')), fn($q) => $q->where('students.school_class_id', request('class')))
            ->when(is_numeric(request('section')), fn($q) => $q->where('students.section_id', request('section')))
            ->when(request('other'), function($query) {
                return $query->where(function($q) {
                    $q->where('students.first_name', 'like', '%' . request('other') . '%')
                      ->orWhere('students.code', 'like', '%' . request('other') . '%')
                      ->orWhere('students.phone', 'like', '%' . request('other') . '%');
                });
            });

        if (!request('branch') && !request('class') && !request('section') && !request('other')) {
            $query->whereRaw('1 = 0');
        }

        return $query->with([
            'createdBy:id,name',
            'updatedBy:id,name',
            'warehouse:id,name',
            'schoolClass:id,name',
            'section:id,name',
        ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('code')->title('Admission No'),
            Column::make('first_name')->title('Name'),
            Column::make('father_name')->content('N/A'),
            Column::make('branch', 'warehouse.name'),
            Column::make('class', 'schoolClass.name'),
            Column::make('email')->content('N/A'),
            Column::make('phone')->content('N/A'),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Collect Fees_' . date('YmdHis');
    }
}
