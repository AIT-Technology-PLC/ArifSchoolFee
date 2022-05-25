@extends('layouts.app')

@section('title')
    Return Details
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
                                    <i class="fas fa-file-invoice"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $return->code ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Return No
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
                                    <i class="fas fa-user"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $return->customer->company_name ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Customer
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
                                    {{ $return->issued_on->toFormattedDateString() ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Issued On
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
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ number_format($return->subtotalPrice, 2) }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    SubTotal Price ({{ userCompany()->currency }})
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="column is-6">
                    <div>
                        <div class="columns is-marginless is-vcentered is-mobile text-purple">
                            <div class="column is-1">
                                <span class="icon is-size-3">
                                    <i class="fas fa-dollar-sign"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ number_format($return->grandTotalPrice, 2) }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Grand Total Price ({{ userCompany()->currency }})
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
                                    {!! is_null($return->description) ? 'N/A' : nl2br(e($return->description)) !!}
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
            @if (!$return->isApproved())
                @can('Approve Return')
                    <x-common.transaction-button
                        :route="route('returns.approve', $return->id)"
                        action="approve"
                        intention="approve this return"
                        icon="fas fa-signature"
                        label="Approve Return"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$return->isAdded())
                @can('Make Return')
                    <x-common.transaction-button
                        :route="route('returns.add', $return->id)"
                        action="add"
                        intention="add products of this return voucher"
                        icon="fas fa-plus-circle"
                        label="Add to Inventory"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            @if ($return->isApproved())
                <x-common.button
                    tag="a"
                    href="{{ route('returns.print', $return->id) }}"
                    target="_blank"
                    mode="button"
                    icon="fas fa-print"
                    label="Print"
                    class="is-small bg-purple has-text-white is-hidden-mobile"
                />
            @endif
            <x-common.button
                tag="a"
                href="{{ route('returns.edit', $return->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            @if ($return->isAdded())
                <x-common.success-message message="Products have been added to the inventory." />
            @elseif (!$return->isApproved())
                <x-common.fail-message message="This Return has not been approved yet." />
            @elseif (!$return->isAdded())
                <x-common.fail-message message="Product(s) listed below are still not added to the inventory." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
