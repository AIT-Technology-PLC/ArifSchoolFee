@extends('layouts.app')

@section('title', 'Pending List')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Pending Request
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($totalPending) }} {{ str()->plural('Request'), $totalPending }}
                        </span>
                    </span>
                </h1>
            </x-slot>
        </x-content.header>
        <x-content.footer>
        <x-common.fail-message :message="session('failedMessage') ?? session('successMessage')" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
