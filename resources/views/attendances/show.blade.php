@extends('layouts.app')

@section('title', 'Attendance Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-user-clock"
                        :data="$attendance->code"
                        label="Attendance No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$attendance->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$attendance->starting_period->toDateString()"
                        label="Starting Period"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$attendance->ending_period->toDateString()"
                        label="Ending Period"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="Details"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$attendance->isApproved())
                    @can('Approve Attendance')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('attendances.approve', $attendance->id)"
                                action="approve"
                                intention="approve this Attendance"
                                icon="fas fa-signature"
                                label="Approve"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if (!$attendance->isCancelled())
                    @can('Cancel Attendance')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('attendances.cancel', $attendance->id)"
                                action="cancel"
                                intention="cancel this Attendance"
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
                        href="{{ route('attendances.edit', $attendance->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
                @if (!$attendance->isApproved() && !$attendance->isCancelled())
                    @can('Import Attendance')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="button"
                                mode="button"
                                @click="$dispatch('open-import-modal') "
                                icon="fas fa-upload"
                                label="Import Attendance"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('imported')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
            @if (!$attendance->isApproved())
                <x-common.fail-message message="This Attendance has not been approved yet." />
            @endif
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    @can('Import Attendance')
        <x-common.import
            title="Import Attendance"
            action="{{ route('attendances.import', $attendance->id) }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
