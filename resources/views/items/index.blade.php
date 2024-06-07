@extends('layouts.app')

@section('title', 'Item Type')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Item Types
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-trademark" />
                        <span>
                            {{ number_format($totalItemTypes) }} {{ str()->plural('item', $totalItemTypes) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import ItemType')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Item Types"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create ItemType')
                <x-common.button
                    tag="a"
                    href="{{ route('items.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Item Type"
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
            title="Import Item Types"
            action="{{ route('items.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
