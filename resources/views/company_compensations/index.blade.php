@extends('layouts.app')

@section('title', 'Company Compensation')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-3 p-lr-0">
            <x-common.total-model
                model="Company Compensations"
                :amount="$totalCompensations"
                icon=""
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header title="company Compensations">
            @can('Create Company Compensation')
                <x-common.button
                    tag="a"
                    href="{{ route('company_compensations.create') }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create Compensation"
                    class="btn-green is-outlined is-small"
                />
            @endcan
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
