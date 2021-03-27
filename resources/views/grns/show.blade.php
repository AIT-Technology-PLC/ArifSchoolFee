@extends('layouts.app')

@section('title')
    GRN Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-file-contract"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $grn->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                GRN No
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
                                <i class="fas fa-hashtag"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $grn->purchase->purchase_no ?? 'N/A' }}
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
                                <i class="fas fa-address-card"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $grn->supplier->company_name ?? 'N/A' }}
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
                            <div class="is-size- has-text-weight-bold">
                                {{ $grn->issued_on->toFormattedDateString() ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Issued On
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
                                GRN Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <a href="{{ route('grns.edit', $grn->id) }}" class="button is-small bg-green has-text-white">
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
            <div class="notification bg-green has-text-white has-text-weight-medium {{ session('successMessage') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-check-circle"></i>
                </span>
                <span>
                    {{ session('successMessage') }}
                </span>
            </div>
            @if ($grn->isGrnApproved() && $grn->isAddedToInventory())
                <div class="message is-success">
                    <p class="message-body">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span>
                            Product(s) listed below have been added to your Inventory.
                        </span>
                    </p>
                </div>
            @endif
            @if ($grn->isGrnApproved() && !$grn->isAddedToInventory())
                @can('Add GRN')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            Product(s) listed below are still not added to your Inventory.
                            <br>
                            Add product(s) automatically by clicking on the button.
                        </p>
                        <form id="formOne" action="{{ route('merchandises.addToInventory', $grn->id . '?model=grns') }}" method="post" novalidate>
                            @csrf
                            <button id="openAddGrnModal" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-plus-circle"></i>
                                </span>
                                <span>
                                    Add to Inventory
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            Product(s) listed below are still not added to your Inventory.
                        </p>
                    </div>
                @endcan
            @endif
            @if (!$grn->isGrnApproved())
                @can('Approve GRN')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            This GRN has not been approved.
                            <br>
                            Click on the button below to approve this GRN.
                        </p>
                        <form id="formOne" action="{{ route('grns.approve', $grn->id) }}" method="post" novalidate>
                            @csrf
                            <button id="openApproveGrnModal" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Approve GRN
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <p class="has-text-grey text-purple is-size-7">
                        This GRN has not been approved.
                    </p>
                @endcan
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Warehouse </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Description </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($grn->grnDetails as $grnDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $grnDetail->warehouse->name }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $grnDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($grnDetail->quantity, 2) }}
                                    {{ $grnDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {!! nl2br(e($grnDetail->description)) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
