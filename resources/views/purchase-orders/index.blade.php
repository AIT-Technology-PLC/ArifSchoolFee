@extends('layouts.app')

@section('title')
    Purchase Order Management
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
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
                            Total Purchase Orders
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column is-6 p-lr-0">
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
        <div class="column is-4 is-offset-2 p-lr-0">
            <div class="box text-green has-text-centered" style="border-left: 2px solid #3d8660;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalClosed }}
                </div>
                <div class="is-uppercase is-size-7">
                    Closed
                </div>
            </div>
        </div>
        <div class="column is-4 p-lr-0">
            <div class="box text-gold has-text-centered" style="border-left: 2px solid #86843d;">
                <div class="is-size-3 has-text-weight-bold">
                    {{ $totalOpen }}
                </div>
                <div class="is-uppercase is-size-7">
                    Open
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
            @include('components.deleted-message', ['model' => 'Purchase Order'])
            <div>
                <table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="[5]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="has-text-centered"><abbr> Purchase Order No </abbr></th>
                            <th><abbr> Status </abbr></th>
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
                                    {{ $purchaseOrder->code ?? 'N/A' }}
                                </td>
                                <td class="is-capitalized">
                                    @if ($purchaseOrder->isPurchaseOrderClosed())
                                        <span class="tag is-small bg-green has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-check-circle"></i>
                                            </span>
                                            <span>
                                                Closed
                                            </span>
                                        </span>
                                    @else
                                        <span class="tag is-small bg-gold has-text-white">
                                            <span class="icon">
                                                <i class="fas fa-exclamation-circle"></i>
                                            </span>
                                            <span>
                                                Still Open
                                            </span>
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
                                        @include('components.delete-button', ['model' => 'purchase-orders',
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
