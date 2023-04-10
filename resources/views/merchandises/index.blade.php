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
                            {{ request()->is('merchandises/on-hand') ? $insights['totalOnHandProducts'] : $insights['totalAvailableProducts'] ?? 0.0 }}
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
                            {{ $insights['totalLimitedProducts'] ?? 0.0 }}
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
                            {{ $insights['totalOutOfStockProducts'] ?? 0.0 }}
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
                            {{ $insights['totalWarehousesInUse'] ?? 0.0 }}
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
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <x-content.header title="Inventory Level Report">
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
                                <option value="{{ $availableWarehouse->id }}">{{ $availableWarehouse->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="icon is-small is-left">
                        <i class="fas fa-warehouse"></i>
                    </div>
                </div>
            </div>
        </x-content.header>
        <div @class([
            'is-hidden' =>
                !request()->is('merchandises/available') &&
                !isFeatureEnabled('Job Management'),
        ])>
            <x-content.footer>
                <x-datatables.filter filters="'level', 'type'">
                    <div class="columns is-marginless is-vcentered">
                        @if (request()->is('merchandises/available'))
                            <div class="column is-3 p-lr-0 pt-0">
                                <x-forms.field class="has-text-centered">
                                    <x-forms.control>
                                        <x-forms.select
                                            id=""
                                            name=""
                                            class="is-size-7-mobile is-fullwidth"
                                            x-model="filters.level"
                                            x-on:change="add('level')"
                                        >
                                            <option
                                                disabled
                                                selected
                                                value=""
                                            >
                                                Level
                                            </option>
                                            <option value="all"> All </option>
                                            @foreach (['Sufficient', 'Limited'] as $level)
                                                <option value="{{ str()->lower($level) }}"> {{ $level }} </option>
                                            @endforeach
                                        </x-forms.select>
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endif
                        @if (isFeatureEnabled('Job Management'))
                            <div class="column is-3 p-lr-0 pt-0">
                                <x-forms.field class="has-text-centered">
                                    <x-forms.control>
                                        <x-forms.select
                                            id=""
                                            name=""
                                            class="is-size-7-mobile is-fullwidth"
                                            x-model="filters.type"
                                            x-on:change="add('type')"
                                        >
                                            <option
                                                disabled
                                                selected
                                                value=""
                                            >
                                                Type
                                            </option>
                                            <option value="all"> All </option>
                                            @foreach (['Finished Goods', 'Raw Material'] as $type)
                                                <option value="{{ str()->lower($type) }}"> {{ $type }} </option>
                                            @endforeach
                                        </x-forms.select>
                                    </x-forms.control>
                                </x-forms.field>
                            </div>
                        @endif
                    </div>
                </x-datatables.filter>
            </x-content.footer>
        </div>
        <div class="tabs is-toggle is-fullwidth has-background-white-bis">
            <ul>
                @can('Read Available Inventory')
                    <li class="available {{ request()->is('merchandises/available') ? 'is-active' : '' }}">
                        <a href="{{ route('merchandises.index', 'available') }}">
                            <span>Available</span>
                        </a>
                    </li>
                @endcan
                @if (isFeatureEnabled('Reservation Management'))
                    @can('Read Reserved Inventory')
                        <li class="reserved {{ request()->is('merchandises/reserved') ? 'is-active' : '' }}">
                            <a href="{{ route('merchandises.index', 'reserved') }}">
                                <span>Reserved</span>
                            </a>
                        </li>
                    @endcan
                    @can('Read On Hand Inventory')
                        <li class="on-hand {{ request()->is('merchandises/on-hand') ? 'is-active' : '' }}">
                            <a href="{{ route('merchandises.index', 'on-hand') }}">
                                <span>On Hand</span>
                            </a>
                        </li>
                    @endcan
                @endif
                @if (isFeatureEnabled('Job Management'))
                    @can('Read Work In Process Inventory')
                        <li class="wip {{ request()->is('merchandises/wip') ? 'is-active' : '' }}">
                            <a href="{{ route('merchandises.index', 'wip') }}">
                                <span>Work in Process</span>
                            </a>
                        </li>
                    @endcan
                @endif
                @can('Read Out Of Stock Inventory')
                    <li class="out-of-stock {{ request()->is('merchandises/out-of-stock') ? 'is-active' : '' }}">
                        <a href="{{ route('merchandises.index', 'out-of-stock') }}">
                            <span>Out of Stock</span>
                        </a>
                    </li>
                @endcan
                @if ($hasExpiredInventory)
                    @can('Read Expired Inventory')
                        <li class="expired {{ request()->is('merchandises/expired') ? 'is-active' : '' }}">
                            <a href="{{ route('merchandises.index', 'expired') }}">
                                <span>Expired</span>
                            </a>
                        </li>
                    @endcan
                @endif
            </ul>
        </div>
    </section>
    <section class="mx-3 m-lr-0">
        <div class="box radius-top-0">
            <div>
                {{ $dataTable->table() }}
            </div>
        </div>
    </section>
    @push('scripts')
        {{ $dataTable->scripts() }}
    @endpush
@endsection
