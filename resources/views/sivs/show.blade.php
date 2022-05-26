@extends('layouts.app')

@section('title', 'SIV Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-file-invoice"
                        :data="$siv->code ?? 'N/A'"
                        label="SIV No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$siv->issued_to ?? 'N/A'"
                        label="Issued To"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$siv->issued_on->toFormattedDateString() ?? 'N/A'"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-question"
                        :data="$siv->purpose ?? 'N/A'"
                        label="Purpose"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-hashtag"
                        :data="$siv->ref_num ?? 'N/A'"
                        label="Ref No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$siv->received_by ?? 'N/A'"
                        label="Receiver Name"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$siv->delivered_by ?? 'N/A'"
                        label="Delivered By"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$siv->description"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            @if ($siv->isApproved())
                <x-common.button
                    tag="a"
                    href="{{ route('sivs.print', $siv->id) }}"
                    target="_blank"
                    mode="button"
                    icon="fas fa-print"
                    label="Print"
                    class="is-small bg-purple has-text-white is-hidden-mobile"
                />
            @elseif(!$siv->isApproved())
                @can('Approve SIV')
                    <x-common.transaction-button
                        :route="route('sivs.approve', $siv->id)"
                        action="approve"
                        intention="approve this SIV"
                        icon="fas fa-signature"
                        label="Approve SIV"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            <x-common.button
                tag="a"
                href="{{ route('sivs.edit', $siv->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($siv->isApproved())
                <x-common.success-message message="This SIV has been approved successfully." />
            @elseif (!$siv->isApproved())
                <x-common.fail-message message="This SIV is not approved." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
