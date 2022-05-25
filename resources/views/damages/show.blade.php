@extends('layouts.app')

@section('title')
    Damage Details
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
                                    {{ $damage->code ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Damage No
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
                                    {{ $damage->issued_on->toFormattedDateString() ?? 'N/A' }}
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
                                    {!! is_null($damage->description) ? 'N/A' : nl2br(e($damage->description)) !!}
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
            @if (!$damage->isApproved())
                @can('Approve Damage')
                    <x-common.transaction-button
                        :route="route('damages.approve', $damage->id)"
                        action="approve"
                        intention="approve this damage claim"
                        icon="fas fa-signature"
                        label="Approve Damage"
                        class="has-text-weight-medium"
                    />
                @endcan
            @elseif(!$damage->isSubtracted())
                @can('Subtract Damage')
                    <x-common.transaction-button
                        :route="route('damages.subtract', $damage->id)"
                        action="subtract"
                        intention="subtract the damaged products"
                        icon="fas fa-minus-circle"
                        label="Subtract from inventory"
                        class="has-text-weight-medium"
                    />
                @endcan
            @endif
            <x-common.button
                tag="a"
                href="{{ route('damages.edit', $damage->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.fail-message :message="session('failedMessage')" />
            <x-common.success-message :message="session('successMessage')" />
            @if ($damage->isSubtracted())
                <x-common.success-message message="Products have been subtracted from inventory." />
            @elseif (!$damage->isApproved())
                <x-common.fail-message message="This Damage has not been approved yet." />
            @elseif (!$damage->isSubtracted())
                <x-common.fail-message message="Product(s) listed below are still not subtracted from your inventory." />
            @endif
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
