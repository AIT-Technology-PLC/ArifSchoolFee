@extends('layouts.app')

@section('title', $pad->name)

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column">
            <x-common.total-model
                model="{{ $pad->abbreviation }}"
                :amount="$transactions->count()"
                icon="{{ $pad->icon }}"
            />
        </div>
        @if ($pad->isInventoryOperationAdd())
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalAdded']"
                    border-color="#3d8660"
                    text-color="text-green"
                    label="Added"
                />
            </div>
        @elseif ($pad->isInventoryOperationSubtract())
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalSubtracted']"
                    border-color="#3d8660"
                    text-color="text-green"
                    label="Subtracted"
                />
            </div>
        @endif

        @if ($pad->isApprovable())
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalApproved']"
                    border-color="#86843d"
                    text-color="text-gold"
                    label="Approved"
                />
            </div>
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalNotApproved']"
                    border-color="#863d63"
                    text-color="text-purple"
                    label="Waiting Approval"
                />
            </div>
        @endif

        @if ($pad->isClosableOnly())
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalApproved']"
                    border-color="#86843d"
                    text-color="text-gold"
                    label="Closed"
                />
            </div>
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalNotApproved']"
                    border-color="#863d63"
                    text-color="text-purple"
                    label="Open"
                />
            </div>
        @endif

        @if ($pad->isCancellable())
            <div class="column p-lr-0">
                <x-common.index-insight
                    :amount="$data['totalCancelled']"
                    border-color="#86843d"
                    text-color="text-gold"
                    label="Cancelled"
                />
            </div>
        @endif
    </div>

    <x-common.content-wrapper>
        <x-content.header title="{{ $pad->name }}">
            @canpad('Create', $pad)
                <x-common.button
                    tag="a"
                    href="{{ route('pads.transactions.create', $pad->id) }}"
                    mode="button"
                    icon="fas fa-plus-circle"
                    label="Create {{ $pad->name }}"
                    class="btn-green is-outlined is-small"
                />
            @endcanpad
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('deleted')" />
            <div>
                {{ $dataTable->table() }}
            </div>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
