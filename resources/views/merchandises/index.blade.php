@extends('layouts.app')

@section('title')
    Merchandise Inventory
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Stock Information by Availability
            </h1>
            <h2 class="subtitle has-text-grey is-size-7">
                On hand, Limited, and Out of Stock
            </h2>
        </div>
        <div class="tabs is-toggle is-fullwidth has-background-white-bis">
            <ul>
                <li id="onHandTab" class="on-hand is-active">
                    <a class="">
                        <span class="icon is-small"><i class="fas fa-check-circle"></i></span>
                        <span>On Hand</span>
                    </a>
                </li>
                <li id="limitedTab" class="limited">
                    <a>
                        <span class="icon is-small"><i class="fas fa-exclamation-circle"></i></span>
                        <span>Limited</span>
                    </a>
                </li>
                <li id="outOfTab" class="out-of-stock">
                    <a>
                        <span class="icon is-small"><i class="fas fa-times-circle"></i></span>
                        <span>Out of Stock</span>
                    </a>
                </li>
            </ul>
        </div>
    </section>

    @include('merchandises.on-hand')

    @include('merchandises.limited')

    @include('merchandises.out-of')

@endsection
