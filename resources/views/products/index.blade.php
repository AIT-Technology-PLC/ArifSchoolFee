@extends('layouts.app')

@section('title', 'Products')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Products
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-th" />
                        <span>
                            {{ number_format($totalProducts) }} {{ str()->plural('product', $totalProducts) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Product')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Product"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Product')
                <x-common.button
                    tag="a"
                    href="{{ route('products.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Product"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('imported')" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
    <x-common.import
        title="Import Products"
        action="{{ route('products.import') }}"
    />
@endsection
@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
