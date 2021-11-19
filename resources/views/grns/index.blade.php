@extends('layouts.app')

@section('title', 'Goods Received Notes')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="GRNS"
                :amount="$totalGrns"
                icon="fas fa-file-import"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalAdded"
                border-color="#3d8660"
                text-color="text-green"
                label="Added"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotAdded"
                border-color="#86843d"
                text-color="text-gold"
                label="Approved"
            />
        </div>
        <div class="column is-3 p-lr-0">
            <x-common.index-insight
                :amount="$totalNotApproved"
                border-color="#863d63"
                text-color="text-purple"
                label="Waiting Approval"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Goods Received Notes">
            @can('Create GRN')
                <x-common.button
                    tag="a"
                    href="{{ route('grns.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create GRN"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
