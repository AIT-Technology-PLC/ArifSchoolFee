@extends('layouts.app')

@section('title', 'Brands')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Brands
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-trademark" />
                        <span>
                            {{ number_format($totalBrands) }} {{ str()->plural('brand', $totalBrands) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Brand')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Brands"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Brand')
                <x-common.button
                    tag="a"
                    href="{{ route('brands.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Brand"
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
    @can('Import Brand')
        <x-common.import
            title="Import Brands"
            action="{{ route('brands.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
