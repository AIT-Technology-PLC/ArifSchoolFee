@extends('layouts.app')

@section('title', 'Adjustments')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model model="adjustments"
                                  :amount="$totalAdjustments"
                                  icon="fas fa-eraser" />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight :amount="$totalAdjusted"
                                    border-color="#3d8660"
                                    text-color="text-green"
                                    label="Adjusted" />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight :amount="$totalNotAdjusted"
                                    border-color="#86843d"
                                    text-color="text-gold"
                                    label="Approved" />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight :amount="$totalNotApproved"
                                    border-color="#863d63"
                                    text-color="text-purple"
                                    label="Waiting Approval" />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Adjustments">
            @can('Create Adjustment')
                <x-common.button tag="a"
                                 href="{{ route('adjustments.create') }}"
                                 type="button"
                                 icon="fas fa-plus-circle"
                                 label="Create Adjustment"
                                 class="btn-green is-outlined is-small" />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.client-datatable date-columns="[4]">
                <x-slot name="headings">
                    <th> # </th>
                    <th class="has-text-centered"> Adjustment No </th>
                    <th> Status </th>
                    <th> Description </th>
                    <th class="has-text-right"> Issued On </th>
                    <th> Prepared By </th>
                    <th> Approved By </th>
                    <th> Adjusted By </th>
                    <th> Edited By </th>
                    <th> Actions </th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($adjustments as $adjustment)
                        <tr class="showRowDetails is-clickable"
                            data-id="{{ route('adjustments.show', $adjustment->id) }}">
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized has-text-centered">
                                {{ $adjustment->code }}
                            </td>
                            <td>
                                @if (!$adjustment->isApproved())
                                    <span class="tag is-small bg-purple has-text-white">
                                        <x-common.icon name="fas fa-clock" />
                                        <span> Waiting Approval </span>
                                    </span>
                                @elseif ($adjustment->isAdjusted())
                                    <span class="tag is-small bg-green has-text-white">
                                        <x-common.icon name="fas fa-check-circle" />
                                        <span> Adjusted </span>
                                    </span>
                                @else
                                    <span class="tag is-small bg-gold has-text-white">
                                        <x-common.icon name="fas fa-exclamation-circle" />
                                        <span> Approved </span>
                                    </span>
                                @endif
                            </td>
                            <td>
                                {!! nl2br(e(substr($adjustment->description, 0, 40))) ?: 'N/A' !!}
                                <span class="is-hidden">
                                    {!! $adjustment->description ?? '' !!}
                                </span>
                            </td>
                            <td class="has-text-right">
                                {{ $adjustment->issued_on->toFormattedDateString() }}
                            </td>
                            <td> {{ $adjustment->createdBy->name ?? 'N/A' }} </td>
                            <td> {{ $adjustment->approvedBy->name ?? 'N/A' }} </td>
                            <td> {{ $adjustment->adjustedBy->name ?? 'N/A' }} </td>
                            <td> {{ $adjustment->updatedBy->name ?? 'N/A' }} </td>
                            <td class="actions">
                                <x-common.action-buttons buttons="all"
                                                         model="adjustments"
                                                         :id="$adjustment->id" />
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
