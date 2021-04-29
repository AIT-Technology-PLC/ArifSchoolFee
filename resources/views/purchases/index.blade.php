@extends('layouts.app')

@section('title')
    Purchase Management
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column is-6">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-shopping-bag"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $totalPurchases }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Purchases
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
                            Create new purchase order, track order and expense
                        </div>
                        <div class="is-size-3">
                            <a href="{{ route('purchases.create') }}" class="button bg-purple has-text-white has-text-weight-medium is-size-7 px-5 py-4 mt-3">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Create New Purchase
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
                Purchase Management
            </h1>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Purchase'])
            <div>
                <table id="table_id" class="is-hoverable is-size-7 display nowrap" data-date="[6]" data-numeric="[5]">
                    <thead>
                        <tr>
                            <th id="firstTarget"><abbr> # </abbr></th>
                            <th class="text-gold"><abbr> Purchase No </abbr></th>
                            <th class="text-blue"><abbr> Purchase Type</abbr></th>
                            <th class="text-purple"><abbr> Payment Method </abbr></th>
                            <th class="has-text-right text-green"><abbr> Total Price </abbr></th>
                            <th class="has-text-right"><abbr> Purchased On </abbr></th>
                            <th><abbr> Prepared By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchases as $purchase)
                            <tr class="showRowDetails is-clickable" data-id="{{ route('purchases.show', $purchase->id) }}">
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-gold has-text-white is-small">
                                        {{ $purchase->purchase_no ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-blue has-text-white is-small">
                                        {{ $purchase->type ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    <span class="tag bg-purple has-text-white is-small">
                                        {{ $purchase->payment_type ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-green has-text-white">
                                        {{ $purchase->company->currency }}.
                                        {{ $purchase->totalPurchasePrice }}
                                    </span>
                                </td>
                                <td class="has-text-right">
                                    {{ $purchase->purchased_on->toFormattedDateString() }}
                                </td>
                                <td> {{ $purchase->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $purchase->updatedBy->name ?? 'N/A' }} </td>
                                <td class="actions">
                                    <a href="{{ route('purchases.show', $purchase->id) }}" data-title="View Details">
                                        <span class="tag is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                Details
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('purchases.edit', $purchase->id) }}" data-title="Modify Purchase Data">
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
                                        @include('components.delete_button', ['model' => 'purchases',
                                        'id' => $purchase->id])
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
