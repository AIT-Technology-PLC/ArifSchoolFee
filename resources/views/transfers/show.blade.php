@extends('layouts.app')

@section('title', 'Transfer Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-exchange-alt"
                        :data="$transfer->code ?? 'N/A'"
                        label="Transfer No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$transfer->issued_on->toFormattedDateString() ?? 'N/A'"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-warehouse"
                        :data="$transfer->transferredFrom->name"
                        label="Transferred From"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-warehouse"
                        :data="$transfer->transferredTo->name"
                        label="Transferred To"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="is_null($transfer->description) ? 'N/A' : nl2br(e($transfer->description))"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            @if (!$transfer->isApproved())
                @can('Approve Transfer')
                    <x-common.transaction-button
                        :route="route('transfers.approve', $transfer->id)"
                        action="approve"
                        intention="approve this transfer"
                        icon="fas fa-signature"
                        label="Approve Transfer"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$transfer->isSubtracted())
                @can('Make Transfer')
                    <x-common.transaction-button
                        :route="route('transfers.subtract', $transfer->id)"
                        action="subtract"
                        intention="subtract products of this transfer"
                        icon="fas fa-minus-circle"
                        label="Subtract from inventory"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$transfer->isAdded())
                @can('Make Transfer')
                    <x-common.transaction-button
                        :route="route('transfers.add', $transfer->id)"
                        action="add"
                        intention="add products of this transfer"
                        icon="fas fa-plus-circle"
                        label="Add to inventory"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            @if ($transfer->isAdded() && !$transfer->isClosed())
                <x-common.transaction-button
                    :route="route('transfers.close', $transfer->id)"
                    action="close"
                    intention="close this transfer"
                    icon="fas fa-ban"
                    label="Close"
                />
            @endif
            @if ($transfer->isSubtracted() && !$transfer->isClosed())
                @can('Create SIV')
                    <x-common.transaction-button
                        :route="route('transfers.convert_to_siv', $transfer->id)"
                        action="attach"
                        intention="attach SIV to this transfer"
                        icon="fas fa-file-export"
                        label="Attach SIV"
                    />
                @endcan
            @endif
            <x-common.button
                tag="a"
                href="{{ route('transfers.edit', $transfer->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($transfer->isAdded())
                <x-common.success-message message="Products have been transferred successfully." />
            @elseif(!$transfer->isApproved())
                <x-common.fail-message message="This Transfer has not been approved yet." />
            @elseif(!$transfer->isSubtracted())
                <x-common.fail-message message="Product(s) listed below are not subtracted from {{ $transfer->transferredFrom->name }}." />
            @elseif(!$transfer->isAdded())
                <x-common.fail-message message="Product(s) listed below are subtracted from {{ $transfer->transferredFrom->name }} but not added to {{ $transfer->transferredTo->name }}." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    @if (isFeatureEnabled('Siv Management') && $sivs->count())
        <x-common.content-wrapper class="mt-5">
            <x-content.header title="Store Issue Vouchers" />
            <x-content.footer>
                <x-common.bulma-table>
                    <x-slot name="headings">
                        <th> # </th>
                        <th class="has-text-centered"> Siv No </th>
                        <th> Status </th>
                        <th> Issued on </th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($sivs as $siv)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized has-text-centered">
                                    <a
                                        class="text-blue has-text-weight-medium"
                                        href="{{ route('sivs.show', $siv->id) }}"
                                    >
                                        {{ $siv->code }}
                                    </a>
                                </td>
                                <td>
                                    @include('components.datatables.siv-status')
                                </td>
                                <td class="is-capitalized">
                                    {{ $siv->issued_on->toFormattedDateString() }}
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.bulma-table>
            </x-content.footer>
        </x-common.content-wrapper>
    @endif
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
