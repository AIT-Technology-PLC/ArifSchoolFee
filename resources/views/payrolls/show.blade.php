@extends('layouts.app')

@section('title', 'Payroll Details')

@section('content')
    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="General Information"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$payroll->isPaid())
                    @can('Approve Payroll')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('payrolls.approve', $payroll->id)"
                                action="{{ $payroll->isApproved() ? 're-process' : 'Process' }}"
                                intention="{{ $payroll->isApproved() ? 're-process' : 'Process' }} this payroll"
                                icon="fas fa-signature"
                                label="{{ $payroll->isApproved() ? 'Re-process' : 'Process' }}"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if ($payroll->isApproved() && !$payroll->isPaid())
                    @can('Pay Payroll')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('payrolls.pay', $payroll->id)"
                                action="pay"
                                intention="pay this payroll"
                                icon="fa-solid fa-circle-check"
                                label="Pay"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                    @can('Read Payroll')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="a"
                                href="{{ route('payrolls.print', $payroll->id) }}"
                                target="_blank"
                                mode="button"
                                icon="fas fa-print"
                                label="Bank Print"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('payrolls.edit', $payroll->id) }}"
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
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
            @if ($payroll->isPaid())
                <x-common.success-message message="This Payroll is paid." />
            @elseif (!$payroll->isApproved())
                <x-common.fail-message message="This Payroll is not processed yet." />
            @elseif (!$payroll->isPaid())
                <x-common.fail-message message="This Payroll is processed but not paid yet." />
            @endif
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-coins"
                        :data="$payroll->code"
                        label="Reference No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fa-solid fa-calendar"
                        :data="$payroll->working_days ?? userCompany()->working_days"
                        label="Working Days"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$payroll->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
                @if ($payroll->isPaid())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-calendar-day"
                            :data="$payroll->paid_at->toFormattedDateString()"
                            label="Paid On"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$payroll->starting_period->toDateString()"
                        label="Starting Period"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$payroll->ending_period->toDateString()"
                        label="Ending Period"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    @if ($payroll->isApproved())
        <x-common.content-wrapper class="mt-5">
            <x-content.header title="Payroll Sheet" />
            <x-content.footer> {{ $dataTable->table() }} </x-content.footer>
        </x-common.content-wrapper>
    @endif

@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
