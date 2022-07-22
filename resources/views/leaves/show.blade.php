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
                @if (!$leaf->isApproved())
                    <x-common.fail-message message="This Leave has not been approved yet." />
                @else
                    <x-common.success-message message="Leave successfully approved." />
                @endif
            </div>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user-slash"
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
                        icon="fas fa-users-slash"
                        :data="$leaf->leaveCategory->name"
                        label="category"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
