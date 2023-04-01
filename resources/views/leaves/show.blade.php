@extends('layouts.app')

@section('title', 'Leave')

@section('content')
    <x-common.content-wrapper>
        <x-content.header
            title="General Information"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$leaf->isApproved())
                    @can('Approve Leave')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('leaves.approve', $leaf->id)"
                                action="approve"
                                intention="approve this leave"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if (!$leaf->isCancelled())
                    @can('Cancel Leave')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('leaves.cancel', $leaf->id)"
                                action="cancel"
                                intention="cancel this leave"
                                icon="fas fa-times-circle"
                                label="Cancel"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('leaves.edit', $leaf->id) }}"
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
                @if (!$leaf->isApproved() && !$leaf->isCancelled())
                    <x-common.fail-message message="This Leave has not been approved yet." />
                @endif
                @if ($leaf->isApproved())
                    <x-common.success-message message="Leave successfully approved." />
                @endif
            </div>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-umbrella-beach"
                        :data="$leaf->code"
                        label="Leave NO"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$leaf->employee->user->name"
                        label="Employee Name"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-umbrella-beach"
                        :data="$leaf->leaveCategory->name"
                        label="Category"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-umbrella-beach"
                        :data="$leaf->isPaidTimeOff() ? 'Paid' : 'Unpaid'"
                        label="Time Off Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-calendar-day"
                        :data="$leaf->starting_period->toDayDateTimeString()"
                        label="Starting Period"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-calendar-day"
                        :data="$leaf->ending_period->toDayDateTimeString()"
                        label="Ending Period"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-clock"
                        :data="$leaf->time_off_amount"
                        label="Time Off {{ userCompany()->paid_time_off_type }}"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$leaf->description"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
