@extends('layouts.app')

@section('title', 'Tender Opportunities')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Tender Opportunities
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-comment-dollar" />
                        <span>
                            {{ number_format($totalTenderOpportunities) }} {{ Str::plural('tender opportunity', $totalTenderOpportunities) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Tender')
                <x-common.button
                    tag="a"
                    href="{{ route('tender-opportunities.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Tender Opportunity"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
