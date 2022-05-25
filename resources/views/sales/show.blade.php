@extends('layouts.app')

@section('title')
    Sale Details
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
                                    <i class="fas fa-hashtag"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $sale->code ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Receipt No
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
                                    <i class="fas fa-credit-card"></i>
                                </span>
                            </div>
                            <div class="column m-lr-20">
                                <div class="is-size- has-text-weight-bold">
                                    {{ $sale->payment_type ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Payment Type
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
                                    {{ $sale->customer->company_name ?? 'N/A' }}
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
                                    {{ $sale->sold_on->toFormattedDateString() ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Sold On
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
                                    {{ number_format($sale->subtotalPrice, 2) }}
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
                                    {{ number_format($sale->grandTotalPrice, 2) }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Grand Total Price ({{ userCompany()->currency }})
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                @if (!userCompany()->isDiscountBeforeVAT())
                    <div class="column is-6">
                        <div>
                            <div class="columns is-marginless is-vcentered is-mobile text-green">
                                <div class="column is-1">
                                    <span class="icon is-size-3">
                                        <i class="fas fa-percentage"></i>
                                    </span>
                                </div>
                                <div class="column m-lr-20">
                                    <div class="is-size- has-text-weight-bold">
                                        {{ number_format($sale->discount * 100, 2) }}%
                                    </div>
                                    <div class="is-uppercase is-size-7">
                                        Discount
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
                                        {{ number_format($sale->grandTotalPriceAfterDiscount, 2) }}
                                    </div>
                                    <div class="is-uppercase is-size-7">
                                        Grand Total Price (After Discount)
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
                <div class="column is-12">
                    <div>
                        <div class="columns is-marginless is-vcentered text-green">
                            <div class="column">
                                <div class="has-text-weight-bold">
                                    Details
                                </div>
                                <div class="is-size-7 mt-3">
                                    {!! is_null($sale->description) ? 'N/A' : nl2br(e($sale->description)) !!}
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
            <x-common.button
                tag="a"
                href="{{ route('sales.edit', $sale->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <div class="notification bg-gold has-text-white has-text-weight-medium {{ session('message') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-times-circle"></i>
                </span>
                <span>
                    {{ session('message') }}
                </span>
            </div>
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
            @if (isFeatureEnabled('Gdn Management'))
                <div class="box has-background-white-bis radius-bottom-0">
                    <h1 class="title is-size-5 text-green has-text-centered">
                        DO for this sale
                    </h1>
                    <div class="table-container has-background-white-bis">
                        <table class="table is-hoverable is-fullwidth is-size-7 has-background-white-bis">
                            <thead>
                                <tr>
                                    <th><abbr> # </abbr></th>
                                    <th><abbr> DO No </abbr></th>
                                    <th><abbr> Status </abbr></th>
                                    <th><abbr> Issued on </abbr></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->gdns as $gdn)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td class="is-capitalized">
                                            <a
                                                class="is-underlined"
                                                href="{{ route('gdns.show', $gdn->id) }}"
                                            >
                                                {{ $gdn->code }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($gdn->isSubtracted())
                                                <span class="tag is-small bg-purple has-text-white">
                                                    Subtracted from inventory
                                                </span>
                                            @else
                                                <span class="tag is-small bg-blue has-text-white">
                                                    Not subtracted from inventory
                                                </span>
                                            @endif
                                        </td>
                                        <td class="is-capitalized">
                                            {{ $gdn->issued_on->toFormattedDateString() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
