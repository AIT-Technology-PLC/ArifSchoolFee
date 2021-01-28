@extends('layouts.app')

@section('title')
    No Internet Connection
@endsection

@section('content')
    <section class="mt-5 mx-3 m-lr-0 mb-5">
        <div class="columns is-marginless is-centered">
            <div class="column is-7 has-text-centered">
                <h1 class="title has-text-grey-lighter is-size-1" style="font-size: 150px !important;">
                    <span class="icon is-large">
                        <i class="fas fa-wifi"></i>
                    </span>
                    <span class="icon is-large" style="margin-left: -85px !important">
                        <i class="fas fa-slash"></i>
                    </span>
                </h1>
                <h1 class="title text-green is-size-4-mobile">
                    <span>
                        No Internet Connection
                    </span>
                </h1>
                <h2 class="subtitle has-text-grey-light has-text-weight-normal is-size-5-mobile">
                    Please check your internet connection and try again.
                </h2>
                <div class="buttons is-centered mt-6">
                    <button id="tryAgainButton" class="button bg-green has-text-white is-uppercase has-text-weight-medium px-5 py-5">
                        Try Again
                    </button>
                </div>
            </div>
        </div>
    </section>
@endsection
