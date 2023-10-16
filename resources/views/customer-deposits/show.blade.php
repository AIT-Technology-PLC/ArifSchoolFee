@extends('layouts.app')

@section('title', 'Deposit')

@section('content')
    <x-common.content-wrapper>
        <x-content.header
            title="General Information"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$customerDeposit->isApproved())
                    @can('Approve Customer Deposit')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('customer-deposits.approve', $customerDeposit->id)"
                                action="approve"
                                intention="approve this deposit"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('customer-deposits.edit', $customerDeposit->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <div>
                <x-common.fail-message :message="session('failedMessage')" />
                <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
                @if (!$customerDeposit->isApproved())
                    <x-common.fail-message message="This deposit has not been approved yet." />
                @endif
                @if ($customerDeposit->isApproved())
                    <x-common.success-message message="Deposit successfully approved." />
                @endif
            </div>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-user"
                        :data="$customerDeposit->customer->company_name"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$customerDeposit->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-calendar-day"
                        :data="$customerDeposit->deposited_at->toFormattedDateString()"
                        label="Deposit Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-sack-dollar"
                        :data="$customerDeposit->amount"
                        label="Amount"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-university"
                        :data="$customerDeposit->bank_name"
                        label="Bank"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-hashtag"
                        :data="$customerDeposit->reference_number"
                        label="Reference No"
                    />
                </div>
                @if ($customerDeposit->attachment)
                    <div class="column is-12">
                        <div class="is-uppercase is-size-7 text-green mb-3">
                            <span class="icon">
                                <i class="fa-solid fa-paperclip"></i>
                            </span>
                            <span>
                                Attachment
                            </span>
                        </div>
                        <figure class="image is-128x128 ml-5">
                            <img src="{{ asset('/storage/' . $customerDeposit->attachment) }}" />
                        </figure>
                    </div>
                @endif
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
