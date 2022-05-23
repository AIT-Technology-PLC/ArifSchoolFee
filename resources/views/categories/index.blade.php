@extends('layouts.app')

@section('title', 'Product Categories')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Product Categories
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($totalProductCategories) }} {{ str()->plural('category', $totalProductCategories) }}
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
                    label="Import Categories"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Product')
                <x-common.button
                    tag="a"
                    href="{{ route('categories.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Category"
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
    @can('Import Product')
        <x-common.import
            title="Import Categories"
            action="{{ route('categories.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
