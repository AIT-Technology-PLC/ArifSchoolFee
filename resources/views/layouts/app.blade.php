<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title> @yield('title') - {{ auth()->user()->employee->company->name }} </title>

    <link rel="shortcut icon" type="image/png" href="{{ asset('img/favicon.png') }}" />
    {{--
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css" integrity="sha256-WLKGWSIJYerRN8tbNGtXWVYnUM5wMJTXD8eG4NtGcDM=" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" integrity="sha512-+4zCK9k+qNFUR5X+cKL9EIR+ZOhtIloNl9GIKS57V1MyNsYpYcUrUeQc9vNfzsWfV28IaLL3i96P9sdNyeRssA==" crossorigin="anonymous" /> --}}
    {{-- Local Assets --}}
    <link href="{{ asset('assets/bulma-0.9.1.css') }}" rel="stylesheet">
    <link href="{{ asset('assets/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
</head>

<body class="has-navbar-fixed-top">

    @include('layouts.header')

    <main>
        <div class="columns is-marginless">
            <div id="menuLeft" class="column is-one-fifth py-5 limit-to-100vh scroller">
                @include('layouts.menu')
            </div>
            <div id="contentRight" class="column bg-lightgreen py-5 limit-to-100vh scroller">
                @yield('content')
                @include('layouts.footer')
            </div>
        </div>
    </main>

    {{-- <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/axios@0.21.0/dist/axios.min.js" integrity="sha256-OPn1YfcEh9W2pwF1iSS+yDk099tYj+plSrCS6Esa9NA=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script> --}}
    {{-- Local Assets --}}
    <script src="{{ asset('assets/jquery-3.5.1.js') }}" defer></script>
    <script src="{{ asset('assets/axios-0.20.0.js') }}" defer></script>
    <script src="{{ asset('assets/charts-2.8.0.js') }}" defer></script>
    <script src="{{ asset('js/app.js') }}" defer></script>

</body>

</html>
