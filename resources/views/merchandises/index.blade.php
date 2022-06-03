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
                            {{ $insights['totalOnHandProducts'] ?? 0.0 }}
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
                                Available, Reserved, On hand, Limited and Out of Stock
                            </h2>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        @if ($warehouses->isNotEmpty())
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
                        @endif
                    </div>
                </div>
            </div>
        </div>
        @if (request()->is('merchandises/available') || userCompany()->plan->isPremium())
            <div>
                <x-content.footer>
                    <x-datatables.filter filters="'level', 'type'">
                        <div class="columns is-marginless is-vcentered">
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
                            @if (userCompany()->plan->isPremium())
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
        @endif
        <div class="tabs is-toggle is-fullwidth has-background-white-bis">
            <ul>
                <li class="available {{ request()->is('merchandises/available') ? 'is-active' : '' }}">
                    <a href="{{ route('merchandises.index', 'available') }}">
                        <span>Available</span>
                    </a>
                </li>
                @if (isFeatureEnabled('Reservation Management'))
                    <li class="reserved {{ request()->is('merchandises/reserved') ? 'is-active' : '' }}">
                        <a href="{{ route('merchandises.index', 'reserved') }}">
                            <span>Reserved</span>
                        </a>
                    </li>
                @endif
                @if (userCompany()->plan->isPremium())
                    <li class="wip {{ request()->is('merchandises/wip') ? 'is-active' : '' }}">
                        <a href="{{ route('merchandises.index', 'wip') }}">
                            <span>Work in Process</span>
                        </a>
                    </li>
                @endif
                <li class="on-hand {{ request()->is('merchandises/on-hand') ? 'is-active' : '' }}">
                    <a href="{{ route('merchandises.index', 'on-hand') }}">
                        <span>On Hand</span>
                    </a>
                </li>
                <li class="out-of-stock {{ request()->is('merchandises/out-of-stock') ? 'is-active' : '' }}">
                    <a href="{{ route('merchandises.index', 'out-of-stock') }}">
                        <span>Out of Stock</span>
                    </a>
                </li>
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
