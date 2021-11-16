@extends('layouts.app')

@section('title', 'Customers')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="customers"
                :amount="$totalCustomers"
                icon="fas fa-user"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="Customers">
            @can('Create Customer')
                <x-common.button
                    tag="a"
                    href="{{ route('customers.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Customer"
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
