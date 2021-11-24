@extends('layouts.app')

@section('title')
    Tender Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-tasks"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $tender->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Tender No
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-columns"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $tender->type ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Type
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-info"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $tender->status ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Status
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-users"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $tender->participants ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Participants
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-file-alt"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $tender->bid_bond_type ?? 'N/A' }} |
                                {{ $tender->bid_bond_amount ?? 'N/A' }} |
                                {{ $tender->bid_bond_validity ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Bid Bond Info
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-user"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $tender->customer->company_name ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Customer
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-spinner"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $tender->tenderChecklistsCompletionRate }}%
                            </div>
                            <div class="is-uppercase is-size-7">
                                Checklist Completion
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-money-bill-wave"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size-7 has-text-weight-bold">
                                {{ $tender->price ? '' : 'N/A' }}
                                {!! nl2br(e($tender->price)) !!}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Price
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-file-invoice-dollar"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size-7 has-text-weight-bold">
                                {{ $tender->price ? '' : 'N/A' }}
                                {!! nl2br(e($tender->payment_term)) !!}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Payment Term
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div>
                    <div class="columns is-marginless is-vcentered text-green">
                        <div class="column">
                            <div class="has-text-weight-bold">
                                Details
                            </div>
                            <div class="is-size-7 mt-3">
                                {!! nl2br(e($tender->description)) ?? 'N/A' !!}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <div class="level">
                <div class="level-left">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                Tender Schedules
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $tender->published_on->toFormattedDateString() ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Publishing Date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green {{ $tender->closing_date->isPast() ? '' : 'text-purple' }}">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $tender->closing_date->toDayDateTimeString() ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Closing Date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $tender->opening_date->toDayDateTimeString() ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Opening Date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $tender->clarify_on ? $tender->clarify_on->toFormattedDateString() : 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Clarification Date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $tender->visit_on ? $tender->visit_on->toFormattedDateString() : 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Visiting Date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $tender->premeet_on ? $tender->premeet_on->toFormattedDateString() : 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Pre-meeting Date
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 has-background-white-bis mt-5">
            <div class="level">
                <div class="level-left">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                Tender Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            @if (!$tender->financial_reading && !$tender->technical_reading)
                                <a
                                    href="{{ route('tender-readings.edit', $tender->id) }}"
                                    class="button is-small btn-green is-outlined has-text-white"
                                >
                                    <span class="icon">
                                        <i class="fas fa-table"></i>
                                    </span>
                                    <span>
                                        Create Readings
                                    </span>
                                </a>
                            @endif
                            <a
                                href="{{ route('tenders.edit', $tender->id) }}"
                                class="button is-small bg-green has-text-white"
                            >
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <span>
                                    Edit
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <x-common.success-message :message="session('deleted')" />
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tender->tenderDetails as $tenderDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $tenderDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($tenderDetail->quantity, 2) }}
                                    {{ $tenderDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {!! nl2br(e($tenderDetail->description)) !!}
                                </td>
                                <td>
                                    <x-common.action-buttons
                                        :buttons="['delete']"
                                        model="tender-details"
                                        :id="$tenderDetail->id"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        @if ($tender->financial_reading || $tender->technical_reading)
            <div class="box radius-bottom-0 mb-0 has-background-white-bis mt-5">
                <div class="level">
                    <div class="level-left">
                        <div class="level-item is-justify-content-left">
                            <div>
                                <h1 class="title text-green has-text-weight-medium is-size-5">
                                    Tender Readings
                                </h1>
                            </div>
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item is-justify-content-left">
                            <div>
                                <a
                                    href="{{ route('tender-readings.edit', $tender->id) }}"
                                    class="button is-small bg-green has-text-white"
                                >
                                    <span class="icon">
                                        <i class="fas fa-table"></i>
                                    </span>
                                    <span>
                                        Edit Readings
                                    </span>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box radius-bottom-0 mb-0 radius-top-0">
                @can('Read Tender Sensitive Data')
                    <div>
                        <h1 class="is-size-5 text-green is-uppercase has-text-weight-medium has-text-centered mb-3"> Financial Reading </h1>
                        <div class="summernote-table">
                            {!! $tender->financial_reading ?? '' !!}
                        </div>

                        <hr class="mt-0 mx-6">
                    </div>
                @endcan
                <div>
                    <h1 class="is-size-5 text-green is-uppercase has-text-weight-medium has-text-centered mb-3"> Technical Reading </h1>
                    <div class="summernote-table">
                        {!! $tender->technical_reading ?? '' !!}
                    </div>
                </div>
            </div>
        @endif
        <div class="box radius-bottom-0 mb-0 has-background-white-bis mt-5">
            <div class="level">
                <div class="level-left">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                Tender Checklists
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <x-common.button
                                tag="a"
                                :href="route('tenders.tender-checklists.create', $tender->id)"
                                mode="button"
                                icon="fas fa-plus-circle"
                                label="Add New Checklist"
                                class="btn-green is-outlined is-small"
                            />
                            <x-common.button
                                tag="a"
                                :href="route('tenders.print', $tender->id)"
                                target="_blank"
                                mode="button"
                                icon="fas fa-print"
                                label="Print"
                                class="btn-green is-outlined is-small is-hidden-mobile"
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <x-common.success-message :message="session('checklistDeleted')" />
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Item </abbr></th>
                            <th><abbr> Status </abbr></th>
                            <th><abbr> Comment </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($tender->tenderChecklists as $tenderChecklist)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $tenderChecklist->generalTenderChecklist->item }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $tenderChecklist->status ?? 'N/A' }}
                                </td>
                                <td>
                                    @if (!$tenderChecklist->generalTenderChecklist->tenderChecklistType->isSensitive())
                                        {!! $tenderChecklist->comment ? nl2br(e($tenderChecklist->comment)) : 'N/A' !!}
                                    @elseif ($tenderChecklist->generalTenderChecklist->tenderChecklistType->isSensitive() &&
                                        auth()->user()->can('Read Tender Sensitive Data'))
                                        {!! $tenderChecklist->comment ? nl2br(e($tenderChecklist->comment)) : 'N/A' !!}
                                    @else
                                        <span class="tag text-purple">
                                            <span class="icon">
                                                <i class="fas fa-times-circle"></i>
                                            </span>
                                            <span>
                                                You don't have permission to see this checklist information
                                            </span>
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    <x-common.action-buttons
                                        :buttons="['edit', 'delete']"
                                        model="tender-checklists"
                                        :id="$tenderChecklist->id"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
