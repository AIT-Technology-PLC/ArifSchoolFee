@extends('layouts.app')

@section('title', 'Fee Master')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Fees Master
                </h1>
            </x-slot>
            @can('Create Fee Master')
                <x-common.button
                    tag="a"
                    href="{{ route('fee-masters.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Fee Master"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
