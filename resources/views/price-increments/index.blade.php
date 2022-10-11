@extends('layouts.app')

@section('title', 'Price Increments')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="price increments"
                :amount="$totalPriceIncrements"
                icon="fas fa-tag"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalApproved"
                border-color="#86843d"
                text-color="text-gold"
                label="Approved"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotApproved"
                border-color="#863d63"
                text-color="text-purple"
                label="Waiting Approval"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Price Increments">
            @can('Create Price Increment')
                <x-common.button
                    tag="a"
                    href="{{ route('price-increments.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Price Increment"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
