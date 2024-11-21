@extends('layouts.app')

@section('title')
    {{ authUser()->is($user->user) ? 'My' : str($user->user->name)->append('\'s') }} Profile
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-12">
            <x-content.header>
                <x-slot name="header">
                    <h1 class="title text-softblue has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-circle-user"></i>
                        </span>
                        <span>
                            {{ authUser()->is($user->user) ? 'My' : str($user->user->name)->append('\'s') }} Profile
                        </span>
                    </h1>
                </x-slot>
            </x-content.header>
            <x-content.footer>
                <div class="columns is-marginless is-multiline">
                    <div class="column is-2">
                        <figure class="image is-128x128 m-auto">
                            <img
                                class="is-rounded"
                                src="{{ asset('img/user.jpg') }}"
                            >
                        </figure>
                    </div>
                    <div class="column is-10 pl-5 p-lr-0">
                        <div class="columns is-marginless is-multiline is-mobile">
                            <div class="column is-6-mobile is-6-tablet is-4-desktop">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <span>
                                        {{ $user->user->name }}
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                                    Name
                                </p>
                            </div>
                            <div class="column is-6-mobile is-6-tablet is-4-desktop">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-cogs"></i>
                                    </span>
                                    <span>
                                        {{ $user->user->roles[0]->name }}
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                                    Role
                                </p>
                            </div>
                            <div class="column is-6-mobile is-6-tablet is-4-desktop">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-user-tie"></i>
                                    </span>
                                    <span>
                                        Position
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                                    {{ $user->position}}
                                </p>
                            </div>
                            <div class="column is-6-mobile is-6-tablet is-4-desktop">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-code-branch"></i>
                                    </span>
                                        {{ $user->user->warehouse->name ?? 'N/A' }}
                                    <span>
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                                    Branch
                                </p>
                            </div>
                            <div class="column is-6-mobile is-6-tablet is-4-desktop">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <span>
                                        {{ $user->user->email }}
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                                    Email
                                </p>
                            </div>
                            <div class="column is-6-mobile is-6-tablet is-4-desktop">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-th"></i>
                                    </span>
                                    <span>
                                        {{ $user->gender ?? 'N/A' }}
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1 is-size-7 is-capitalized">
                                    Gender
                                </p>
                            </div>
                            <div class="column is-6-mobile is-6-tablet is-4-desktop">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <span>
                                        {{ $user->enabled ? 'Enabled' : 'Disabled' }}
                                    </span>
                                </p>
                                <p class="has-text-weight-bold {{ $user->enabled ? 'text-softblue' : 'text-purple' }} ml-1">
                                    System Access
                                </p>
                            </div>
                            <div class="column is-6-mobile is-6-tablet is-4-desktop">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <span>
                                        {{ $user->phone ?? 'N/A' }}
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                                    Phone
                                </p>
                            </div>
                            <div class="column is-6-mobile is-6-tablet is-4-desktop">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <span>
                                        {{ $user->address ?? 'N/A' }}
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1 is-size-7">
                                    Address
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-content.footer>
        </div>
    </div>
@endsection
