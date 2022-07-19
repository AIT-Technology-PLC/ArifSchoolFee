@extends('layouts.app')

@section('title', 'Warnings')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Warnings
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-circle-exclamation" />
                        <span>
                            {{ number_format($totalWarnings) }} {{ str()->plural('warning', $totalWarnings) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Warning')
                <x-common.button
                    tag="a"
                    href="{{ route('warnings.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Warning"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
