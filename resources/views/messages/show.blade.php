@extends('layouts.app')

@section('title', 'Message Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-blue has-text-weight-medium is-size-6">
                    <span class="icon mr-1">
                        <i class="fas fa-message"></i>
                    </span>
                    <span>
                       Message Detail
                    </span>
                </h1>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <div class="columns is-marginless is-multiline is-mobile">
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-layer-group"
                        :data="str()->ucfirst($message->subject) ?? 'N/A'"
                        label="{{__('public.Subject')}}"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-sort"
                        :data="str()->ucfirst($message->type) ?? 'N/A'"
                        label="{{__('public.Message Type')}}"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$message->created_at->toFormattedDateString() ?? 'N/A'"
                        label="{{__('public.Sent At')}}"
                    />
                </div>
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="$message->message_content ?? 'N/A' "
                        label="{{__('public.Message')}}"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header
            title="{{__('Public.Sent To')}}"
            is-mobile
        >
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
