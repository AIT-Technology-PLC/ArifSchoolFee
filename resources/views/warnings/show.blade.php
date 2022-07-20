@extends('layouts.app')

@section('title', 'Warning Letter')

@section('content')
    <x-common.content-wrapper>
        <x-content.header
            title="General Information"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$warning->isApproved())
                    @can('Approve Warning')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('warnings.approve', $warning->id)"
                                action="approve"
                                intention="approve this warning"
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
                        href="{{ route('warnings.edit', $warning->id) }}"
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
                @if (!$warning->isApproved())
                    <x-common.fail-message message="This Warning has not been approved yet." />
                @else
                    <x-common.success-message message="Warning successfully approved." />
                @endif
            </div>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-circle-exclamation"
                        :data="$warning->code"
                        label="Warning NO"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-sort"
                        :data="$warning->type"
                        label="Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$warning->employee->user->name"
                        label="Employee Name"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$warning->issued_on->toFormattedDateString()"
                        label="Issued On"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Letter" />
        <x-content.footer>
            <div class="column is-12">
                {!! $warning->letter !!}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
