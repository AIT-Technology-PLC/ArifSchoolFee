@extends('layouts.app')

@section('title', 'Messages')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Email/SMS
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-message" />
                        <span>
                            {{ number_format($totalMessages) }} {{ str()->plural('message'), $totalMessages }}
                        </span>
                    </span>
                </h1>
            </x-slot>
            @can('Create Message')
                <x-common.button
                    tag="a"
                    href="{{ route('messages.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Send Email/SMS"
                    class="btn-blue is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <x-datatables.filter filters="'type'">
                <div class="columns is-marginless is-vcentered">
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.type"
                                    x-on:change="add('type')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Types
                                    </option>
                                    @foreach (['SMS', 'Email', 'Both (Email and SMS)'] as $type)
                                        <option value="{{ str()->lower($type) }}"> {{ $type }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
                </div>
            </x-datatables.filter>
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
