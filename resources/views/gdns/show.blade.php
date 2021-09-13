@extends('layouts.app')

@section('title')
    Delivery Order Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
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
                                {{ $gdn->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                DO No
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if ($enabledFeatures->contains('Sale Management'))
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
                                    {{ $gdn->sale->receipt_no ?? 'N/A' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Receipt No
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
                                <i class="fas fa-credit-card"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $gdn->payment_type ?? 'N/A' }}
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
                                {{ $gdn->customer->company_name ?? 'N/A' }}
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
                                {{ $gdn->issued_on->toFormattedDateString() ?? 'N/A' }}
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
                                <i class="fas fa-hand-holding-usd"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($gdn->getPaymentInCash(), 2) }}
                                ({{ (float) $gdn->cash_received_in_percentage }}%)
                            </div>
                            <div class="is-uppercase is-size-7">
                                In Cash ({{ $gdn->company->currency }})
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
                                <i class="fas fa-money-check"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ number_format($gdn->getPaymentInCredit(), 2) }}
                                ({{ $gdn->credit_payable_in_percentage }}%)
                            </div>
                            <div class="is-uppercase is-size-7">
                                On Credit ({{ $gdn->company->currency }})
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
                                {{ number_format($gdn->subtotalPrice, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                SubTotal Price ({{ $gdn->company->currency }})
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
                                {{ number_format($gdn->grandTotalPrice, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Grand Total Price ({{ $gdn->company->currency }})
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
                                    {{ number_format($gdn->discount * 100, 2) }}%
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
                                    {{ number_format($gdn->grandTotalPriceAfterDiscount, 2) }}
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
                                {!! $gdn->description !!}
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
                                Delivery Order Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            @if ($gdn->isApproved())
                                <button id="printGdn" class="button is-small bg-purple has-text-white is-hidden-mobile  " onclick="openInNewTab('/gdns/{{ $gdn->id }}/print')">
                                    <span class="icon">
                                        <i class="fas fa-print"></i>
                                    </span>
                                    <span>
                                        Print
                                    </span>
                                </button>
                            @endif
                            @if ($gdn->isSubtracted())
                                <a href="{{ route('gdns.sivs.create', $gdn->id) }}" class="button is-small btn-green is-outlined has-text-white">
                                    <span class="icon">
                                        <i class="fas fa-file-export"></i>
                                    </span>
                                    <span>
                                        Attach SIV
                                    </span>
                                </a>
                            @endif
                            <a href="{{ route('gdns.edit', $gdn->id) }}" class="button is-small bg-green has-text-white">
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
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="notification bg-lightpurple text-purple {{ session('failedMessage') ? '' : 'is-hidden' }}">
                @foreach ((array) session('failedMessage') as $message)
                    <span class="icon">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <span>
                        {{ $message }}
                    </span>
                    <br>
                @endforeach
            </div>
            <div class="notification bg-green has-text-white has-text-weight-medium {{ session('successMessage') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-check-circle"></i>
                </span>
                <span>
                    {{ session('successMessage') }}
                </span>
            </div>
            @if ($gdn->isApproved() && $gdn->isSubtracted())
                <div class="box is-shadowless bg-lightgreen has-text-left mb-6">
                    <p class="has-text-grey text-green is-size-6">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span>
                            Products have been subtracted from inventory.
                        </span>
                    </p>
                </div>
            @endif
            @if ($gdn->isApproved() && !$gdn->isSubtracted())
                @can('Subtract GDN')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            Product(s) listed below are still not subtracted from your inventory.
                            <br>
                            Click on the button below to subtract product(s) from the inventory.
                        </p>
                        <form id="formOne" action="{{ route('gdns.subtract', $gdn->id) }}" method="post" novalidate>
                            @csrf
                            <button data-type="Delivery Order" data-action="execute" data-description="" class="swal button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-minus-circle"></i>
                                </span>
                                <span>
                                    Subtract from inventory
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-6">
                            <span class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <span>
                                Product(s) listed below are still not subtracted from your inventory.
                            </span>
                        </p>
                    </div>
                @endcan
            @endif
            @if (!$gdn->isApproved())
                @can('Approve GDN')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            This Delivery Order has not been approved.
                            <br>
                            Click on the button below to approve this Delivery Order.
                        </p>
                        <form id="formOne" action="{{ route('gdns.approve', $gdn->id) }}" method="post" novalidate>
                            @csrf
                            <button data-type="Delivery Order" data-action="approve" data-description="" class="swal button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Approve
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-6">
                            <span class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <span>
                                This Delivery Order has not been approved.
                            </span>
                        </p>
                    </div>
                @endcan
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> From </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            @if (userCompany()->isDiscountBeforeVAT())
                                <th><abbr> Discount </abbr></th>
                            @endif
                            <th><abbr> Total </abbr></th>
                            <th><abbr> Description </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($gdn->gdnDetails as $gdnDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $gdnDetail->warehouse->name }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $gdnDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($gdnDetail->quantity, 2) }}
                                    {{ $gdnDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ $gdn->company->currency }}.
                                    {{ number_format($gdnDetail->unit_price, 2) }}
                                </td>
                                @if (userCompany()->isDiscountBeforeVAT())
                                    <td>
                                        {{ number_format($gdnDetail->discount * 100, 2) }}%
                                    </td>
                                @endif
                                <td>
                                    {{ number_format($gdnDetail->totalPrice, 2) }}
                                </td>
                                <td>
                                    {!! nl2br(e($gdnDetail->description)) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
