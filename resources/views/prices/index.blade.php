@extends('layouts.app')

@section('title', 'Prices')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="products"
                :amount="$totalProducts"
                icon="fas fa-th"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalWithPrice"
                border-color="#3d8660"
                text-color="text-green"
                label="Products With Price"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalNoPrices"
                border-color="#863d63"
                text-color="text-purple"
                label="No Price Products"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Prices">
            @can('Import Price')
                <x-common.button
                    tag="button"
                    mode="button"
                    @click="$dispatch('open-import-modal') "
                    icon="fas fa-upload"
                    label="Import Price"
                    class="btn-green is-outlined is-small"
                />
            @endcan
            @can('Create Price')
                <x-common.button
                    tag="a"
                    href="{{ route('prices.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Prices"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? (session('successMessage') ?? session('imported'))" />
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />

            <x-datatables.filter filters="'status'">
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
                                        Status
                                    </option>
                                    @foreach (['With Price', 'No Price'] as $status)
                                        <option value="{{ str()->lower($status) }}"> {{ $status }} </option>
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

    @can('Import Price')
        <x-common.import
            title="Import Price"
            action="{{ route('prices.import') }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
