@extends('layouts.app')

@section('title')
    Dashboard - Onrica Inventory
@endsection

@section('content')
<div class="columns is-marginless">
    <div class="column is-3">
        <div class="box text-green">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-truck-loading"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        59
                    </div>
                    <div class="is-uppercase is-size-7">
                        Material Types
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Raw Materials Out of Stock
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="box text-purple">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-boxes"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        13
                    </div>
                    <div class="is-uppercase is-size-7">
                        Product Types
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Finished Products Available
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="box text-gold">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-sync-alt"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        27
                    </div>
                    <div class="is-uppercase is-size-7">
                        Product Types
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                Products In Process
            </div>
        </div>
    </div>
    <div class="column is-3">
        <div class="box text-blue">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-tools"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        27
                    </div>
                    <div class="is-uppercase is-size-7">
                        Items
                    </div>
                </div>
            </div>
            <hr class="my-4">
            <div class="is-size-7 is-uppercase has-text-grey">
                MRO Items Available
            </div>
        </div>
    </div>
</div>

<div class="columns is-marginless">
    <div class="column is-4">
        <div class="box text-green">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-check-circle"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        59
                    </div>
                    <div class="is-uppercase is-size-7">
                        Products
                    </div>
                    <hr class="my-3">
                    <div class="is-size-7 is-uppercase has-text-grey">
                        Available in Stock
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-4">
        <div class="box text-purple">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-times-circle"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        13
                    </div>
                    <div class="is-uppercase is-size-7">
                        Products
                    </div>
                    <hr class="my-3">
                    <div class="is-size-7 is-uppercase has-text-grey">
                        Out Of Stock
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-4">
        <div class="box text-gold">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        27
                    </div>
                    <div class="is-uppercase is-size-7">
                        Products
                    </div>
                    <hr class="my-3">
                    <div class="is-size-7 is-uppercase has-text-grey">
                        Limited Stock
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="columns is-marginless is-vcentered">
    <div class="column is-8">
        <div class="box">
            <p class="text-green has-text-weight-medium mb-4">
                Stock Level
            </p>
            {!! $chartjs->render() !!}
        </div>
    </div>
    <div class="column is-4">
        <div class="box">
            <p class="text-green has-text-weight-medium mb-4">
                Products By Warehouse
            </p>
            {!! $pie->render() !!}
        </div>
    </div>
</div>
@endsection
