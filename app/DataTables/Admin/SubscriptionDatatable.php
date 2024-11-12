<?php

namespace App\DataTables\Admin;

use App\Models\Subscription;
use App\Traits\DataTableHtmlBuilder;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Services\DataTable;

class SubscriptionDatatable extends DataTable
{
    use DataTableHtmlBuilder;

    public function dataTable($query)
    {
        return datatables()
            ->eloquent($query)
            ->setRowClass('is-clickable')
            ->editColumn('starts_on', fn($subscription) => $subscription->starts_on?->toFormattedDateString())
            ->editColumn('expiry date', fn($subscription) => $subscription->expiresOn?->toFormattedDateString())
            ->editColumn('status', fn($subscription) => view('components.datatables.subscription-status', compact('subscription')))
            ->editColumn('school', fn($subscription) => str()->ucfirst($subscription->company->name))
            ->editColumn('plan', fn($subscription) => str()->ucfirst($subscription->plan->name))
            ->editColumn('created_at', fn($subscription) => $subscription->created_at->toFormattedDateString())
            ->addIndexColumn();
    }

    public function query(Subscription $subscription)
    {
        return $subscription
            ->newQuery()
            ->select('subscriptions.*')
            ->with([
                'plan',
                'company',
            ]);
    }

    protected function getColumns()
    {
        return [
            Column::computed('#'),
            Column::make('school', 'company.name')->content('N/A'),
            Column::make('plan', 'plan.name')->content('N/A'),
            Column::make('starts_on')->title('Start Date')->content('N/A'),
            Column::make('expiry date')->content('N/A'),
            Column::make('months'),
            Column::make('status'),
            Column::make('created_at'),
        ];
    }

    protected function filename(): string
    {
        return 'Subscriptions' . date('YmdHis');
    }
}

