<?php

namespace App\DataTables;

use App\Models\Contact;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ContactDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->editColumn('registered on', fn($contact) => $contact->created_at->toFormattedDateString())
            ->editColumn('added by', fn($contact) => $contact->createdBy->name)
            ->editColumn('edited by', fn($contact) => $contact->updatedBy->name)
            ->editColumn('actions', function ($contact) {
                return view('components.common.action-buttons', [
                    'model' => 'contacts',
                    'id' => $contact->id,
                    'buttons' => ['delete', 'edit'],
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Contact $contact)
    {
        return $contact->newQuery()
            ->select('contacts.*')
            ->with([
                'createdBy:id,name',
                'updatedBy:id,name',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('name'),
            Column::make('tin')->content('N/A')->title('TIN No'),
            Column::make('phone')->content('N/A'),
            Column::make('email')->content('N/A'),
            Column::make('registered on', 'created_at'),
            Column::make('added by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];
    }

    protected function filename(): string
    {
        return 'Contact_' . date('YmdHis');
    }
}
