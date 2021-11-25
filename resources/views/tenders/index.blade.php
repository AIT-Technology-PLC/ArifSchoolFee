@extends('layouts.app')

@section('title', 'Tenders')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Tenders
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-project-diagram" />
                        <span>
                            {{ number_format($totalTenders) }} {{ Str::plural('tender', $totalTenders) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Tender')
                <x-common.button
                    tag="a"
                    href="{{ route('tenders.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Tender"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.client-datatable date-columns="[14,15,16]">
                <x-slot name="headings">
                    <th><abbr> # </abbr></th>
                    <th><abbr> Tender No </abbr></th>
                    <th><abbr> Type </abbr></th>
                    <th class="text-blue"><abbr> Status </abbr></th>
                    <th class="text-purple"><abbr> Checklist Completion </abbr></th>
                    <th><abbr> Customer </abbr></th>
                    <th class="has-text-centered"><abbr> Participants </abbr></th>
                    <th><abbr> Bid Bond Type</abbr></th>
                    <th><abbr> Bid Bond Amount</abbr></th>
                    <th><abbr> Bid Bond Validity</abbr></th>
                    <th><abbr> Price Description </abbr></th>
                    <th><abbr> Payment Term </abbr></th>
                    <th class="has-text-centered"><abbr> Items </abbr></th>
                    <th><abbr> Description </abbr></th>
                    <th class="has-text-right"><abbr> Published On </abbr></th>
                    <th class="has-text-right text-purple"><abbr> Closing Date </abbr></th>
                    <th class="has-text-right"><abbr> Opening Date </abbr></th>
                    <th><abbr> Prepared By </abbr></th>
                    <th><abbr> Edited By </abbr></th>
                    <th><abbr> Actions </abbr></th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($tenders as $tender)
                        <tr
                            class="showRowDetails is-clickable"
                            data-id="{{ route('tenders.show', $tender->id) }}"
                        >
                            <td> {{ $loop->index + 1 }} </td>
                            <td> {{ $tender->code ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $tender->type }} </td>
                            <td class="is-capitalized">
                                <span class="tag is-small bg-blue has-text-white">
                                    {{ $tender->status }}
                                </span>
                            </td>
                            <td class="is-capitalized">
                                <span class="tag is-small bg-purple has-text-white">
                                    {{ $tender->tenderChecklistsCompletionRate }}%
                                </span>
                            </td>
                            <td> {{ $tender->customer->company_name ?? 'N/A' }} </td>
                            <td class="has-text-centered"> {{ $tender->participants ?? 'N/A' }} </td>
                            <td> {{ $tender->bid_bond_type ?? 'N/A' }} </td>
                            <td> {{ $tender->bid_bond_amount ?? 'N/A' }} </td>
                            <td> {{ $tender->bid_bond_validity ?? 'N/A' }} </td>
                            <td> {!! nl2br(e($tender->price)) !!} </td>
                            <td> {!! nl2br(e($tender->payment_term)) !!} </td>
                            <td class="has-text-centered"> {{ $tender->tender_details_count }} </td>
                            <td class="description">
                                {!! nl2br(e(substr($tender->description, 0, 40))) ?? 'N/A' !!}
                                <span class="is-hidden">
                                    {!! $tender->description ?? '' !!}
                                </span>
                            </td>
                            <td class="has-text-right"> {{ $tender->published_on->toFormattedDateString() }} </td>
                            <td class="has-text-right">
                                <span
                                    style="background-color: inherit"
                                    class="tag is-small {{ $tender->closing_date->isPast() ? '' : 'bg-purple has-text-white' }}"
                                >
                                    {{ $tender->closing_date->toDayDateTimeString() }}
                                </span>
                            </td>
                            <td class="has-text-right">
                                <span
                                    class="tag is-small"
                                    style="background-color: inherit"
                                >
                                    {{ $tender->opening_date->toDayDateTimeString() }}
                                </span>
                            </td>
                            <td> {{ $tender->createdBy->name ?? 'N/A' }} </td>
                            <td> {{ $tender->updatedBy->name ?? 'N/A' }} </td>
                            <td class="actions">
                                <x-common.action-buttons
                                    buttons="all"
                                    model="tenders"
                                    :id="$tender->id"
                                />
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
