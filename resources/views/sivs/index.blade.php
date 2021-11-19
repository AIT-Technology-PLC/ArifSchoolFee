@extends('layouts.app')

@section('title', 'Store Issue Vouchers')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-4 p-lr-0">
            <x-common.total-model
                model="Store Issue Vouchers"
                :amount="$totalSivs"
                icon="fas fa-file-export"
            />
        </div>
        <div class="column is-4 p-lr-0">
            <x-common.index-insight
                :amount="$totalApproved"
                border-color="#3d8660"
                text-color="text-green"
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
        <x-content.header title="Store Issue Vouchers">
            @can('Create SIV')
                <x-common.button
                    tag="a"
                    href="{{ route('sivs.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create SIV"
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
