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
    <title> @yield('title') - {{ authUser()->isAdmin() ? 'Admin Panel' : userCompany()->name }} </title>
    @include('assets.css')
    @include('pwa.tags')
</head>

<body class="has-navbar-fixed-top">
    @include('layouts.header')

    <main
        x-data="sideMenu"
        @toggle-side-menu-on-laptop.window="toggleOnLaptop"
    >
        <div
            x-data="toggler"
            @side-menu-opened.window="toggle"
            class="columns is-marginless"
        >
            <div
                id="menuLeft"
                class="column is-one-fifth-desktop is-full-touch py-5 scroller is-overflow is-hidden-touch"
                x-bind:class="{ 'is-hidden-touch': isHidden, 'is-hidden-desktop': !isSideMenuOpenedOnLaptop }"
            >
                <x-dynamic-component :component="authUser()->sideMenuComponent" />
            </div>
            <div
                id="contentRight"
                class="column bg-lightgreen py-5 scroller"
                x-bind:class="{ 'is-hidden-touch': !isHidden }"
                x-bind:style="!isSideMenuOpenedOnLaptop && { left: 0 }"
            >
                @yield('content')
                @include('layouts.footer')
            </div>
        </div>
        <x-common.connection-status />
    </main>

    @include('assets.js')
</body>

</html>
