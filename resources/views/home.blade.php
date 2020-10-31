@extends('layouts.app')

@section('content')
<div class="columns is-marginless">
    <div class="column">
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
                    <div class="is-size-7 is-uppercase has-text-weight-light mt-5">
                        Raw Materials To Reorder
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column">
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
                    <div class="is-size-7 is-uppercase has-text-weight-light mt-5">
                        Available Finished Products
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column">
        <div class="box text-gold">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-spinner"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        27
                    </div>
                    <div class="is-uppercase is-size-7">
                        Product Types
                    </div>
                    <div class="is-size-7 is-uppercase has-text-weight-light mt-5">
                        Products Being Processed
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="columns is-marginless mt-6">
    <div class="column is-8">
        <div>
            {!! $chartjs->render() !!}
        </div>
    </div>
    <div class="column is-3">
        <div>
            {!! $pie->render() !!}
        </div>
    </div>
</div>
@endsection
