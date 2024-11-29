@extends('layouts.app')

@section('title', 'Notice Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-6">
                    <span class="icon mr-1">
                        <i class="fas fa-comments"></i>
                    </span>
                    <span>
                        Notice Detail
                    </span>
                </h1>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-heading"
                        :data="$notice->title"
                        label="Title"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-alt"
                        :data="$notice->notice_date"
                        label="Notice Date"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-code-branch"
                        :data="implode(', ', $notice->warehouses->pluck('name')->toArray())"
                        label="Branches"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user-group"
                        :data="implode(', ', $notice->recipientTypes->pluck('type')->toArray())"
                        label="To"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$notice->message"
                        label="Notice"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
