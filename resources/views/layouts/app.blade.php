<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title> @yield('title') - {{ auth()->user()->employee->company->name }} </title>
    @include('assets.css')
    @include("pwa.tags")
</head>

<body class="has-navbar-fixed-top">
    @include('layouts.header')

    <main>
        <div class="columns is-marginless">
            <div id="menuLeft" class="column is-one-fifth py-5 limit-to-100vh scroller is-hidden-mobile">
                @include('layouts.menu')
            </div>
            <div id="contentRight" class="column bg-lightgreen py-5 limit-to-100vh scroller">
                @yield('content')
                @include('layouts.footer')
            </div>
        </div>
    </main>

    @include('assets.js')
</body>

</html>
