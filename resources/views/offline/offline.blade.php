<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title> No Internet Connection </title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.png') }}" />

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" integrity="sha256-WLKGWSIJYerRN8tbNGtXWVYnUM5wMJTXD8eG4NtGcDM=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" />
    {{-- Local Assets --}}
    <link href="{{ asset('css/app.css?' . filemtime('css/app.css')) }}" rel="stylesheet">
    @include("pwa.tags")
</head>

<body>
    <main>
        <section class="hero bg-lightgreen is-fullheight">
            <div class="hero-body">
                <div class="container has-text-centered">
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
                        <button id="backButton" class="button btn-green is-outlined has-text-white is-uppercase has-text-weight-medium px-5 py-5">
                            Back
                        </button>
                        <button id="refreshButton" class="button bg-green has-text-white is-uppercase has-text-weight-medium px-5 py-5">
                            Try Again
                        </button>
                    </div>
                </div>
            </div>
        </section>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.0/dist/axios.min.js" integrity="sha256-OPn1YfcEh9W2pwF1iSS+yDk099tYj+plSrCS6Esa9NA=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js" integrity="sha512-AA1Bzp5Q0K1KanKKmvN/4d3IRKVlv9PYgwFPvm32nPO6QS8yH1HO7LbgB1pgiOxPtfeg5zEn2ba64MUcqJx6CA==" crossorigin="anonymous" defer></script>
    {{-- Local Assets --}}
    <script src="{{ asset('js/app.js?' . filemtime('js/app.js')) }}" defer></script>
    <script src="{{ asset('js/caller.js?' . filemtime('js/caller.js')) }}" defer></script>

</body>

</html>
