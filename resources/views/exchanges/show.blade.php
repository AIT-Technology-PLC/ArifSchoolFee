@extends('layouts.app')

@section('title', 'Exchange Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                @if ($model == 'gdn')
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-file-invoice"
                            :data="$exchange->exchangeable?->code ?? 'N/A'"
                            label="Delivery Order No"
                        />
                    </div>
                @elseif($model == 'sale')
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-cash-register"
                            :data="$exchange->exchangeable?->code ?? 'N/A'"
                            label="Invoice No"
                        />
                    </div>
                @endif
                @foreach ($exchange->customFieldValues as $field)
                    <div class="column is-6">
                        <x-common.show-data-section
                            :icon="$field->customField->icon"
                            :data="$field->value"
                            :label="$field->customField->label"
                        />
                    </div>
                @endforeach
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="Details"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$exchange->isApproved() && authUser()->can(['Approve Exchange', 'Execute Exchange']))
                    <x-common.dropdown-item>
                        <x-common.transaction-button
                            :route="route('exchanges.approve_and_execute', $exchange->id)"
                            action="approve & execute"
                            intention="approve & execute this exchange"
                            icon="fas fa-plus-circle"
                            label="Approve & Execute"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @elseif (!$exchange->isApproved())
                    @can('Approve Exchange')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('exchanges.approve', $exchange->id)"
                                action="approve"
                                intention="approve this exchange"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @elseif(!$exchange->isExecuted())
                    @can('Execute Exchange')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('exchanges.execute', $exchange->id)"
                                action="execute"
                                intention="execute products of this exchange voucher"
                                icon="fas fa-plus-circle"
                                label="Execute"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if ($exchange->isExecuted())
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('exchanges.print', $exchange->id) }}"
                            target="_blank"
                            mode="button"
                            icon="fas fa-print"
                            label="Print"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('exchanges.edit', $exchange->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($exchange->isExecuted())
                <x-common.success-message message="Exchange have been Executed." />
            @elseif (!$exchange->isApproved())
                <x-common.fail-message message="This Exchange has not been approved yet." />
            @elseif (!$exchange->isExecuted())
                <x-common.fail-message message="This Exchange is still not executed." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
