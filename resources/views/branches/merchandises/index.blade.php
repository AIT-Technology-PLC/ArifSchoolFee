@extends('layouts.app')

@section('title')
    Current Merchandise Inventory
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-check-circle"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $insights['totalOnHandProducts'] }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Product Types
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="is-size-7 is-uppercase has-text-grey">
                    Available In Stock
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box text-gold">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-exclamation-circle"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $insights['totalLimitedProducts'] }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Product Types
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="is-size-7 is-uppercase has-text-grey">
                    Limited Stock
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box text-purple">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-times-circle"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $insights['totalOutOfStockProducts'] }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Product Types
                        </div>
                    </div>
                </div>
                <hr class="my-4">
                <div class="is-size-7 is-uppercase has-text-grey">
                    Out Of Stock
                </div>
            </div>
        </div>
    </div>
    <section x-data="inventoryTypeToggler">
        <div class="mt-3 mx-3 m-lr-0">
            <x-content.header title="{{ $warehouse->name }} Inventory Level Report">
                <div class="field">
                    <div class="control has-icons-left">
                        <div class="select is-small">
                            <select
                                x-data="changeWarehouse"
                                @change="change"
                            >
                                <option
                                    value="0"
                                    selected
                                >All Warehouses</option>
                                @foreach ($warehouses as $availableWarehouse)
                                    <option
                                        value="{{ $availableWarehouse->id }}"
                                        @selected($warehouse->id == $availableWarehouse->id)
                                    >{{ $availableWarehouse->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="icon is-small is-left">
                            <i class="fas fa-warehouse"></i>
                        </div>
                    </div>
                </div>
            </x-content.header>
            <div class="tabs is-toggle is-fullwidth has-background-white-bis">
                <ul>
                    <li
                        id="onHandTab"
                        class="on-hand is-active"
                        :class="{ 'is-active': isOnHand }"
                    >
                        <a @click="showOnHand">
                            <span class="icon is-small"><i class="fas fa-check-circle"></i></span>
                            <span>On Hand</span>
                        </a>
                    </li>
                    <li
                        id="outOfTab"
                        class="out-of-stock"
                        :class="{ 'is-active': !isOnHand }"
                    >
                        <a @click="showOutOf">
                            <span class="icon is-small"><i class="fas fa-times-circle"></i></span>
                            <span>Out of Stock</span>
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        @include('warehouses.merchandises.on-hand')

        @can('Read Out Of Stock Inventory')
            @include('warehouses.merchandises.out-of')
        @endcan
    </section>
@endsection
