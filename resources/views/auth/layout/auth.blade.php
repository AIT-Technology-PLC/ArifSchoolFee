<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1"
    />
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >
    <title> @yield('title') </title>
    @include('assets.css')
    @include("pwa.tags")
</head>

<body>
    @yield('content')

    <div
        id="backOnline"
        style="position: fixed; bottom: 0;left: 0;right: 0;"
        class="bg-green has-text-white has-text-centered is-hidden"
    >
        <span class="icon">
            <i class="fas fa-globe"></i>
        </span>
        <span>
            Back Online
        </span>
    </div>
    <div
        id="backOffline"
        style="position: fixed; bottom: 0;left: 0;right: 0;"
        class="has-background-grey-dark has-text-white has-text-centered is-hidden"
    >
        <span class="icon">
            <i class="fas fa-exclamation-circle"></i>
        </span>
        <span>
            You're Offline
        </span>
    </div>

    @include('assets.js')
</body>

</html>
