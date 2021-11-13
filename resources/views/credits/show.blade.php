@extends('layouts.app')

@section('title', 'Credit Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information">
            @can('Update Credit')
                <x-common.button
                    tag="a"
                    href="{{ route('credits.edit', $credit->id) }}"
                    mode="button"
                    icon="fas fa-pen"
                    label="Edit Credit"
                    class="btn-purple is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        :data="$credit->code"
                        label="Credit No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$credit->gdn->code ?? 'N/A'"
                        label="Delivery Order No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$credit->customer->company_name"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-spinner"
                        data="{{ number_format($credit->settlement_percentage, 2) }}%"
                        label="Settlement Percentage"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$credit->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$credit->due_date->toFormattedDateString()"
                        label="Due Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$credit->last_settled_at ? $credit->last_settled_at->toFormattedDateString() : 'N/A'"
                        label="Last Settlement Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($credit->credit_amount, 2) }}"
                        label="Credit Amount in {{ userCompany()->currency }}"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($credit->credit_amount_settled, 2) }}"
                        label="Settled Amount in {{ userCompany()->currency }}"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-check"
                        data="{{ number_format($credit->credit_amount_unsettled, 2) }}"
                        label="Unsettled Amount in {{ userCompany()->currency }}"
                    />
                </div>
                @if (!is_null($credit->description))
                    <div class="column is-12">
                        <x-common.show-data-section
                            type="long"
                            :data="$credit->description"
                            label="Details"
                        />
                    </div>
                @endif
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Credit Settlements">
            @if (!$credit->isSettled())
                @can('Settle Credit')
                    <x-common.button
                        tag="a"
                        href="{{ route('credits.credit-settlements.create', $credit->id) }}"
                        mode="button"
                        icon="fas fa-money-check"
                        label="Add Settlement"
                        class="is-small btn-purple is-outlined"
                    />
                @endcan
            @endif
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('deleted')" />
            @if ($credit->isSettled())
                <x-common.success-message message="This credit is fully settled." />
            @elseif ($credit->settlement_percentage)
                <x-common.fail-message message="This credit is partially settled." />
            @else
                <x-common.fail-message message="No settlements was made to this credit." />
            @endif
            <x-common.client-datatable date-columns="[4]">
                <x-slot name="headings">
                    <th> # </th>
                    <th> Method </th>
                    <th> Bank </th>
                    <th> Reference No </th>
                    <th> Settlement Date </th>
                    <th class="has-text-right"> Amount </th>
                    <th> Description </th>
                    <th> Actions </th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($credit->creditSettlements as $creditSettlement)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized"> {{ $creditSettlement->method }} </td>
                            <td class="is-capitalized"> {{ $creditSettlement->bank_name ?? 'N/A' }} </td>
                            <td class="is-capitalized"> {{ $creditSettlement->reference_number ?? 'N/A' }} </td>
                            <td> {{ $creditSettlement->settled_at->toFormattedDateString() }}</td>
                            <td class="has-text-right">{{ userCompany()->currency . '. ' . number_format($creditSettlement->amount, 2) }}</td>
                            <td> {!! nl2br(e($creditSettlement->description)) !!} </td>
                            <td>
                                <x-common.action-buttons
                                    :buttons="['edit', 'delete']"
                                    model="credit-settlements"
                                    :id="$creditSettlement->id"
                                />
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
