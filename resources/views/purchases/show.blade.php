@extends('layouts.app')

@section('title')
    Purchase Details
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
                            <div class="has-text-weight-bold">
                                {{ $purchase->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Purchase No
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
                                <i class="fas fa-shopping-bag"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="has-text-weight-bold">
                                {{ $purchase->type ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Purchase TYpe
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
                            <div class="has-text-weight-bold">
                                {{ $purchase->payment_type ?? 'N/A' }}
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
                                <i class="fas fa-address-card"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="has-text-weight-bold">
                                {{ $purchase->supplier->company_name ?? 'N/A' }}
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
                            <div class="has-text-weight-bold">
                                {{ $purchase->purchased_on->toFormattedDateString() ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Purchased On
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
                                {{ number_format($purchase->subtotalPrice, 2) }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                SubTotal Price ({{ userCompany()->currency }})
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @if (!$purchase->isImported())
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
                                    {{ number_format($purchase->grandTotalPrice, 2) }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Grand Total Price ({{ userCompany()->currency }})
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
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
                                    {{ number_format($purchase->discount * 100, 2) }}%
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Discount
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif
            @if (!$purchase->isImported() && !userCompany()->isDiscountBeforeVAT())
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
                                    {{ number_format($purchase->grandTotalPriceAfterDiscount, 2) }}
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
                                {!! is_null($purchase->description) ? 'N/A' : nl2br(e($purchase->description)) !!}
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
                                Purchase Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            @if (!$purchase->isClosed())
                                <x-common.transaction-button
                                    :route="route('purchases.close', $purchase->id)"
                                    action="close"
                                    intention="close this purchase"
                                    icon="fas fa-ban"
                                    label="Close"
                                />
                                <a
                                    href="{{ route('purchases.convert_to_grn', $purchase->id) }}"
                                    class="button btn-purple is-outlined is-small"
                                >
                                    <span class="icon">
                                        <i class="fas fa-plus"></i>
                                    </span>
                                    <span>
                                        New GRN
                                    </span>
                                </a>
                            @endif
                            <a
                                href="{{ route('purchases.edit', $purchase->id) }}"
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
                        @foreach ($purchase->purchaseDetails as $purchaseDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span>
                                        {{ $purchaseDetail->product->name }}
                                    </span>
                                    <span class="has-text-grey {{ $purchaseDetail->product->code ? '' : 'is-hidden' }}">
                                        ({{ $purchaseDetail->product->code }})
                                    </span>
                                </td>
                                <td>
                                    {{ number_format($purchaseDetail->quantity, 2) }}
                                    {{ $purchaseDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ userCompany()->currency }}.
                                    {{ number_format($purchaseDetail->unit_price, 2) }}
                                </td>
                                @if (userCompany()->isDiscountBeforeVAT())
                                    <td>
                                        {{ number_format($purchaseDetail->discount * 100, 2) }}%
                                    </td>
                                @endif
                                <td>
                                    {{ number_format($purchaseDetail->totalPrice, 2) }}
                                </td>
                                <td>
                                    <x-common.action-buttons
                                        :buttons="['delete']"
                                        model="purchase-details"
                                        :id="$purchaseDetail->id"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="box has-background-white-bis radius-bottom-0">
                <h1 class="title is-size-5 text-green has-text-centered">
                    GRNs for this purchase
                </h1>
                <div class="table-container has-background-white-bis">
                    <table class="table is-hoverable is-fullwidth is-size-7 has-background-white-bis">
                        <thead>
                            <tr>
                                <th><abbr> # </abbr></th>
                                <th><abbr> GRN No </abbr></th>
                                <th><abbr> Status </abbr></th>
                                <th><abbr> Issued on </abbr></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($purchase->grns as $grn)
                                <tr>
                                    <td> {{ $loop->index + 1 }} </td>
                                    <td class="is-capitalized">
                                        <a
                                            class="is-underlined"
                                            href="{{ route('grns.show', $grn->id) }}"
                                        >
                                            {{ $grn->code }}
                                        </a>
                                    </td>
                                    <td>
                                        @if ($grn->isAdded())
                                            <span class="tag is-small bg-purple has-text-white">
                                                Added to inventory
                                            </span>
                                        @else
                                            <span class="tag is-small bg-blue has-text-white">
                                                Not added to inventory
                                            </span>
                                        @endif
                                    </td>
                                    <td class="is-capitalized">
                                        {{ $grn->issued_on->toFormattedDateString() }}
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
