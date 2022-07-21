@extends('layouts.app')

@section('title', 'Warnings')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="Warnings"
                :amount="$totalWarnings"
                icon="fas fa-exclamation-circle"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalInitalWarning"
                border-color="#86843d"
                text-color="text-gold"
                label="Initial"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalAffirmedWarning"
                border-color="#3d8660"
                text-color="text-green"
                label="Affirmation"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalFinalWarning"
                border-color="#863d3e"
                text-color="text-brown"
                label="Final"
            />
        </div>
    </div>
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-green has-text-weight-medium is-size-5">
                    Warnings 
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
            <x-common.fail-message :message="session('failedMessage')" />
            <x-datatables.filter filters="'status', 'type'">
                <div class="columns is-marginless is-vcentered">
                    <div class="column is-3 p-lr-0 pt-0">
                        <x-forms.field class="has-text-centered">
                            <x-forms.control>
                                <x-forms.select
                                    id=""
                                    name=""
                                    class="is-size-7-mobile is-fullwidth"
                                    x-model="filters.status"
                                    x-on:change="add('status')"
                                >
                                    <option
                                        disabled
                                        selected
                                        value=""
                                    >
                                        Statuses
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Approved', 'Waiting Approval'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
                                    @endforeach
                                </x-forms.select>
                            </x-forms.control>
                        </x-forms.field>
                    </div>
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
                                        Type
                                    </option>
                                    <option value="all"> All </option>
                                    @foreach (['Initial Warning', 'Affirmation Warning', 'Final Warning'] as $type)
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
