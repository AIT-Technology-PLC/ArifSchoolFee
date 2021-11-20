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

    <x-common.connection-status />

    @include('assets.js')
</body>

</html>
