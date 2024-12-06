@extends('layouts.app')

@section('title', 'Payment Gateway')

@section('content')
    <x-common.content-wrapper>
        <x-content.header
            title="Payment Gateway"
            is-mobile
        >
            <x-common.dropdown name="API Setting">
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('admin.arifpay-settings.create') }}"
                        mode="button"
                        icon="fas fa-credit-card"
                        label="ArifPay Setting"
                        class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('admin.paypal-settings.create') }}"
                        mode="button"
                        icon="fab fa-paypal"
                        label="PayPal Setting"
                        class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
            </x-common.dropdown>
            <x-common.button
                tag="a"
                href="{{ route('admin.payment-gateways.create') }}"
                mode="button"
                icon="fas fa-plus-circle"
                label="Create Merchant ID"
                class="btn-softblue is-outlined is-small"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
