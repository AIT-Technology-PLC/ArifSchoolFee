@extends('layouts.app')

@section('title', 'Contacts')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Contacts
                    <span class="tag bg-green has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-address-card" />
                        <span>
                            {{ number_format($totalContacts) }} {{ str()->plural('Contact', $totalContacts) }}
                        </span>
                        <span class="is-hidden-mobile">
                            &nbsp; registered
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Import Contact')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal')"
                    icon="fas fa-upload"
                    label="Import Contacts"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Contact')
                <x-common.button
                    tag="a"
                    href="{{ route('contacts.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Contact"
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
    @can('Import Contact')
        <x-common.import
            title="Import Contacts"
            action="{{ route('contacts.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
