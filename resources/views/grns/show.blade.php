@extends('layouts.app')

@section('title')
    GRN Details
@endsection

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-file-import"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $grn->code ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    GRN No
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (isFeatureEnabled('Purchase Management'))
                    <div class="column is-6">
                        <div>
                            <div class="columns is-marginless is-vcentered is-mobile text-green">
                                <div class="column is-1">
                                    <span class="icon is-size-3">
                                        <i class="fas fa-hashtag"></i>
                                    </span>
                                </div>
                                <div class="column m-lr-20">
                                    <div class="is-size- has-text-weight-bold">
                                        {{ $grn->purchase->code ?? 'N/A' }}
                                    </div>
                                    <div class="is-uppercase is-size-7">
                                        Purchase No
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-address-card"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $grn->supplier->company_name ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Supplier
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-green">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-calendar-day"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $grn->issued_on->toFormattedDateString() ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Issued On
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-12">
                    <div>
                        <div class="columns is-marginless is-vcentered text-green">
                            <div class="column">
                                <div class="has-text-weight-bold">
                                    Details
                                </div>
                                <div class="is-size-7 mt-3">
                                    {!! $grn->description !!}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            @if (!$grn->isApproved())
                @can('Approve GRN')
                    <x-common.transaction-button
                        :route="route('grns.approve', $grn->id)"
                        action="approve"
                        intention="approve this GRN"
                        icon="fas fa-signature"
                        label="Approve GRN"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$grn->isAdded())
                @can('Add GRN')
                    <x-common.transaction-button
                        :route="route('grns.add', $grn->id)"
                        action="add"
                        intention="add products of this GRN"
                        icon="fas fa-plus-circle"
                        label="Add to Inventory"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            <x-common.button
                tag="a"
                href="{{ route('grns.edit', $grn->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($grn->isAdded())
                <x-common.success-message message="Product(s) listed below have been added to your Inventory." />
            @elseif (!$grn->isApproved())
                <x-common.fail-message message="This GRN has not been approved yet." />
            @elseif (!$grn->isAdded())
                <x-common.fail-message message="Product(s) listed below are still not added to your Inventory." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
