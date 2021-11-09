@extends('layouts.app')

@section('title', 'Credits')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="credits"
                :amount="$totalCredits"
                icon="fas fa-money-check"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalSettled"
                border-color="#3d8660"
                text-color="text-green"
                label="Settled"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalPartiallySettled"
                border-color="#86843d"
                text-color="text-gold"
                label="Partial Settlements"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotSettledAtAll"
                border-color="#863d63"
                text-color="text-purple"
                label="No Settlements"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Credits">
            @can('Create Credit')
                <x-common.button
                    tag="a"
                    href="{{ route('credits.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Credit"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted') ?? session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
