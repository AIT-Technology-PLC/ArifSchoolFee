@extends('layouts.app')

@section('title')
    Access Permission Denied
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0 mb-5">
        <div class="columns is-marginless is-centered">
            <div class="column is-7 has-text-centered">
                <h1 class="title has-text-grey-lighter is-size-1" style="font-size: 150px !important;">
                    4<span class="text-softblue">0</span>3
                </h1>
                <h1 class="title text-softblue">
                    <span class="icon is-large">
                        <i class="fas fa-exclamation-circle"></i>
                    </span>
                    <span>
                        Access Denied
                    </span>
                </h1>
                <h2 class="subtitle has-text-grey-light has-text-weight-light">
                    Sorry, but you don't have permission to access this page you can go back to
                    <a class="text-green has-text-weight-bold" onclick="history.back()">previous page</a>.
                </h2>
                <h2 class="subtitle has-text-grey-light has-text-weight-normal">
                    Please refer to your <span class="text-green has-text-weight-bold">System Administrator</span> for more.
                </h2>

            </div>
        </div>
    </section>
@endsection
