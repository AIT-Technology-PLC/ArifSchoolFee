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
        @if (!isset($warehouse))
            <div class="column">
                <div class="box text-blue">
                    <div class="columns is-marginless is-vcentered is-mobile">
                        <div class="column has-text-centered is-paddingless">
                            <span class="icon is-large is-size-1">
                                <i class="fas fa-warehouse"></i>
                            </span>
                        </div>
                        <div class="column is-paddingless">
                            <div class="is-size-3 has-text-weight-bold">
                                {{ $totalWarehouseInUse }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Warehouses
                            </div>
                        </div>
                    </div>
                    <hr class="my-4">
                    <div class="is-size-7 is-uppercase has-text-grey">
                        Total Warehouses In Use
                    </div>
                </div>
            </div>
        @endif
    </div>
    <section x-data="inventoryTypeToggler">
        <div class="mt-3 mx-3 m-lr-0">
            <div class="box radius-bottom-0 mb-0 has-background-white-bis">
                <div class="level">
                    <div class="level-left">
                        <div class="level-item">
                            <div>
                                <h1 class="title text-green has-text-weight-medium is-size-5">
                                    Current Inventory Level in {{ isset($warehouse) ? $warehouse->name : 'all Warehouses' }}
                                </h1>
                                <div></div>
                                <h2 class="subtitle has-text-grey is-size-7">
                                    On hand, Limited, and Out of Stock
                                </h2>
                            </div>
                        </div>
                    </div>
                    <div class="level-right">
                        <div class="level-item is-justify-content-left">
                            <div class="field">
                                <div class="control has-icons-left">
                                    <div class="select">
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
                                                    {{ ($warehouse->id ?? '') == $availableWarehouse->id ? 'selected' : '' }}
                                                >{{ $availableWarehouse->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="icon is-small is-left">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
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
