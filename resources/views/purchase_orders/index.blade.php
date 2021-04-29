@extends('layouts.app')

@section('title')
    Purchase Order Management
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-file-invoice"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalPurchaseOrders }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Purchase
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column is-paddingless has-text-centered">
                        <div class="is-uppercase is-size-7">
                            Create new Purchase Order for bulk and long range purchases
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('purchase-orders.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Purchase Order
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Purchase Order Management
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Purchase Order'])
            <div>
                <table id="table_id" class="is-hoverable is-size-7 display nowrap" data-date="[6]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th id="firstTarget"><abbr> # </abbr></th>
                            <th class="text-gold"><abbr> Purchase Order No </abbr></th>
                            <th class="text-purple"><abbr> Status </abbr></th>
                            <th><abbr> Customer </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Received On </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchaseOrders as $purchaseOrder)
                            <tr class="showRowDetails is-clickable" data-id="{{ route('purchase-orders.show', $purchaseOrder->id) }}">
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-gold has-text-white">
                                        {{ $purchaseOrder->code ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    @if ($purchaseOrder->isPurchaseOrderClosed())
                                        <span class="tag is-small bg-purple has-text-white">
                                            Closed PO
                                        </span>
                                    @else
                                        <span class="tag is-small bg-blue has-text-white">
                                            Open PO
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $purchaseOrder->customer->company_name ?? 'N/A' }}
                                </td>
                                <td>
                                    {!! nl2br(e(substr($purchaseOrder->description, 0, 40))) ?? 'N/A' !!}
                                    <span class="is-hidden">
                                        {!! $purchaseOrder->description ?? '' !!}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    {{ $purchaseOrder->received_on->toFormattedDateString() }}
                                </td>
                                <td> {{ $purchaseOrder->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $purchaseOrder->updatedBy->name ?? 'N/A' }} </td>
                                <td class="actions">
                                    <a href="{{ route('purchase-orders.show', $purchaseOrder->id) }}" data-title="View Details">
                                        <span class="tag is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('purchase-orders.edit', $purchaseOrder->id) }}" data-title="Modify Purchase Order Data">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <span>
                                        @include('components.delete_button', ['model' => 'purchase-orders',
                                        'id' => $purchaseOrder->id])
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
