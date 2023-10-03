<?php

namespace App\DataTables;

use App\Models\Expense;
use App\Traits\DataTableHtmlBuilder;
use Illuminate\Support\Arr;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class ExpenseDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->setRowAttr([
                'data-url' => fn($expense) => route('expenses.show', $expense->id),
                'x-data' => 'showRowDetails',
                'x-on:click' => 'showDetails',
            ])
            ->editColumn('branch', fn($expense) => $expense->warehouse->name)
            ->editColumn('status', fn($expense) => view('components.datatables.expense-status', compact('expense')))
            ->editColumn('total price', function ($expense) {
                if (userCompany()->isDiscountBeforeTax()) {
                    return money($expense->grandTotalPrice);
                }

                return money($expense->grandTotalPriceAfterDiscount);
            })
            ->editColumn('supplier', fn($expense) => $expense->supplier->company_name ?? 'N/A')
            ->editColumn('contact', fn($expense) => $expense->contact->name ?? 'N/A')
            ->editColumn('tax_type', fn($expense) => $expense->taxModel->type ?? 'N/A')
            ->editColumn('reference_no', fn($expense) => $expense->reference_number ?? 'N/A')
            ->editColumn('payment_type', fn($expense) => $expense->payment_type ?? 'N/A')
            ->editColumn('issued_on', fn($expense) => $expense->issued_on->toFormattedDateString())
            ->editColumn('description', fn($expense) => view('components.datatables.searchable-description', ['description' => $expense->description]))
            ->editColumn('prepared by', fn($expense) => $expense->createdBy->name)
            ->editColumn('edited by', fn($expense) => $expense->updatedBy->name)
            ->editColumn('approved by', fn($expense) => $expense->approvedBy->name ?? 'N/A')
            ->editColumn('actions', function ($expense) {
                return view('components.common.action-buttons', [
                    'model' => 'expenses',
                    'id' => $expense->id,
                    'buttons' => 'all',
                ]);
            })
            ->addIndexColumn();
    }

    public function query(Expense $expense)
    {
        return $expense
            ->newQuery()
            ->select('expenses.*')
            ->when(is_numeric(request('branch')), fn($query) => $query->where('expenses.warehouse_id', request('branch')))
            ->when(!is_null(request('paymentType')) && request('paymentType') != 'all', fn($query) => $query->where('expenses.payment_type', request('paymentType')))
            ->when(!is_null(request('taxType')) && request('taxType') != 'all', fn($query) => $query->where('expenses.tax_id', request('taxType')))
            ->when(request('status') == 'approved', fn($query) => $query->approved())
            ->when(request('status') == 'waiting approval', fn($query) => $query->notApproved())
            ->with([
                'warehouse:id,name',
                'createdBy:id,name',
                'updatedBy:id,name',
                'approvedBy:id,name',
                'supplier:id,company_name',
                'contact:id,name',
                'taxModel:id,type',
            ]);
    }

    protected function getColumns()
    {
        $columns = [
            Column::computed('#'),
            Column::make('branch', 'warehouse.name')->visible(false),
            Column::make('code')->className('has-text-centered')->title('Expense No'),
            Column::computed('status'),
            Column::computed('total price'),
            Column::make('supplier', 'supplier.company_name'),
            Column::make('contact', 'contact.name'),
            Column::make('tax_type', 'taxModel.type'),
            Column::make('reference_number')->visible(false),
            Column::make('issued_on'),
            Column::computed('total price')->visible(false),
            Column::make('payment_type')->visible(false),
            Column::make('bank_name')->visible(false)->content('N/A'),
            Column::make('bank_reference_number')->visible(false)->content('N/A'),
            Column::make('description')->visible(false),
            Column::make('prepared by', 'createdBy.name'),
            Column::make('edited by', 'updatedBy.name')->visible(false),
            Column::make('approved by', 'approvedBy.name')->visible(false),
            Column::computed('actions')->className('actions'),
        ];

        return Arr::where($columns, fn($column) => $column != null);
    }

    protected function filename(): string
    {
        return 'Expense_' . date('YmdHis');
    }
}
