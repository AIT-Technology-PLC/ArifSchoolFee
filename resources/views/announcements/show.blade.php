@extends('layouts.app')

@section('title', 'Announcement Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header
            title="General Information"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @if (!$announcement->isApproved())
                    @can('Approve Announcement')
                        <x-common.dropdown-item>
                            <x-common.transaction-button
                                :route="route('announcements.approve', $announcement->id)"
                                action="approve"
                                intention="approve this announcement"
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
                        href="{{ route('announcements.edit', $announcement->id) }}"
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
                @if (!$announcement->isApproved())
                    <x-common.fail-message message="This Announcement has not been approved yet." />
                @else
                    <x-common.success-message message="Announcement successfully send." />
                @endif
            </div>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-bullhorn"
                        :data="$announcement->code"
                        label="Announcement No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-th"
                        :data="$announcement->title"
                        label="Title"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-warehouse"
                        :data="implode(', ', $announcement->warehouses->pluck('name')->toArray())"
                        label="Branches"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Content" />
        <x-content.footer>
            <div class="column is-12">
                {!! $announcement->content !!}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
