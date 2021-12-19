@extends('layouts.app')

@section('title')
    Sale Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
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
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <div class="level">
                <div class="level-left">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                Sale Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <a
                                href="{{ route('sales.edit', $sale->id) }}"
                                class="button is-small bg-green has-text-white"
                            >
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <span>
                                    Edit
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0 pb-0">
            <div class="notification bg-gold has-text-white has-text-weight-medium {{ session('message') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-times-circle"></i>
                </span>
                <span>
                    {{ session('message') }}
                </span>
            </div>
            <x-common.success-message :message="session('deleted')" />
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7 has-text-centered">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            @if (userCompany()->isDiscountBeforeVAT())
                                <th><abbr> Discount </abbr></th>
                            @endif
                            <th><abbr> Total </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sale->saleDetails as $saleDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span>
                                        {{ $saleDetail->product->name }}
                                    </span>
                                    <span class="has-text-grey {{ $saleDetail->product->code ? '' : 'is-hidden' }}">
                                        ({{ $saleDetail->product->code }})
                                    </span>
                                </td>
                                <td>
                                    {{ number_format($saleDetail->quantity, 2) }}
                                    {{ $saleDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ userCompany()->currency }}.
                                    {{ number_format($saleDetail->unit_price, 2) }}
                                </td>
                                @if (userCompany()->isDiscountBeforeVAT())
                                    <td>
                                        {{ number_format($saleDetail->discount * 100, 2) }}%
                                    </td>
                                @endif
                                <td>
                                    {{ userCompany()->currency }}.
                                    {{ number_format($saleDetail->totalPrice, 2) }}
                                </td>
                                <td>
                                    <x-common.action-buttons
                                        :buttons="['delete']"
                                        model="sale-details"
                                        :id="$saleDetail->id"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
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
        </div>
    </section>
@endsection
