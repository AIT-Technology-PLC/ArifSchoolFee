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
                                {{ $purchase->purchase_no ?? 'N/A' }}
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
                            <div class="has-text-weight-bold">
                                {{ $purchase->totalPurchasePrice }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Total Price ({{ $purchase->company->currency }})
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
                                <div class="has-text-weight-bold">
                                    {{ $purchase->totalPurchasePriceWithVAT ?? '0.00' }}
                                </div>
                                <div class="is-uppercase is-size-7">
                                    Total Price with VAT ({{ $purchase->company->currency }})
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
                            @if ($purchase->isPurchaseManual())
                                <a href="{{ route('purchases.grns.create', $purchase->id) }}" class="button is-small bg-purple has-text-white">
                                    <span class="icon">
                                        <i class="fas fa-plus-circle"></i>
                                    </span>
                                    <span>
                                        New GRN
                                    </span>
                                </a>
                            @endif
                            <a href="{{ route('purchases.edit', $purchase->id) }}" class="button is-small bg-green has-text-white">
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
            @if (!$purchase->isPurchaseManual())
                @if (!$purchase->isAddedToInventory())
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            Product(s) listed below are still not added to your Inventory.
                            <br>
                            Add product(s) automatically by clicking on the button.
                        </p>
                        <button id="openAddToInventoryModal" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                            <span class="icon">
                                <i class="fas fa-plus"></i>
                            </span>
                            <span>
                                Add to Inventory
                            </span>
                        </button>
                        @error('warehouse_id')
                            <span class="help has-text-danger" role="alert">
                                To add purchased products to Inventory, please select a warehouse.
                            </span>
                        @enderror
                    </div>
                @else
                    <div class="message is-success">
                        <p class="message-body">
                            <span class="icon">
                                <i class="fas fa-check-circle"></i>
                            </span>
                            <span>
                                Product(s) listed below have been to your Inventory.
                            </span>
                        </p>
                    </div>
                @endif
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7 has-text-centered">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            <th><abbr> Total </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->purchaseDetails as $purchaseDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $purchaseDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($purchaseDetail->quantity, 2) }}
                                    {{ $purchaseDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ $purchase->company->currency }}.
                                    {{ number_format($purchaseDetail->unit_price, 2) }}
                                </td>
                                <td>
                                    {{ $purchase->company->currency }}.
                                    {{ number_format($purchaseDetail->quantity * $purchaseDetail->unit_price, 2) }}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @if ($purchase->isPurchaseManual())
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
                                            <a class="is-underlined" href="{{ route('grns.show', $grn->id) }}">
                                                {{ $grn->code }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($grn->isAddedToInventory())
                                                <span class="tag is-small bg-purple has-text-white">
                                                    {{ $grn->status ?? 'N/A' }}
                                                </span>
                                            @else
                                                <span class="tag is-small bg-blue has-text-white">
                                                    {{ $grn->status ?? 'N/A' }}
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
            @endif
        </div>
    </section>
@endsection
