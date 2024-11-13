@extends('layouts.app')

@section('title')
    {{ authUser()->is($user->user) ? 'My' : str($user->user->name)->append('\'s') }} Profile
@endsection

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-12">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-softblue has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-circle-user"></i>
                        </span>
                        <span>
                            {{ authUser()->is($user->user) ? 'My' : str($user->user->name)->append('\'s') }} Profile
                        </span>
                    </h1>
                </x-slot:header>
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
                        <div class="columns is-marginless is-multiline">
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-user"></i>
                                    </span>
                                    <span>
                                        Name
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1">
                                    {{ $user->user->name }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-cogs"></i>
                                    </span>
                                    <span>
                                        Role
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1">
                                    {{ $user->user->roles[0]->name }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-user-tie"></i>
                                    </span>
                                    <span>
                                        Position
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1">
                                    {{ $user->position}}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-code-branch"></i>
                                    </span>
                                    <span>
                                        Branch
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1">
                                    {{ $user->user->warehouse->name ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-at"></i>
                                    </span>
                                    <span>
                                        Email
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1">
                                    {{ $user->user->email }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-th"></i>
                                    </span>
                                    <span>
                                        Gender
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1 is-capitalized">
                                    {{ $user->gender ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-lock"></i>
                                    </span>
                                    <span>
                                        System Access
                                    </span>
                                </p>
                                <p class="has-text-weight-bold {{ $user->enabled ? 'text-softblue' : 'text-purple' }} ml-1">
                                    {{ $user->enabled ? 'Enabled' : 'Disabled' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-phone"></i>
                                    </span>
                                    <span>
                                        Phone
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1">
                                    {{ $user->phone ?? 'N/A' }}
                                </p>
                            </div>
                            <div class="column is-4">
                                <p class="has-text-grey is-size-7 is-uppercase">
                                    <span class="icon">
                                        <i class="fas fa-map-marker-alt"></i>
                                    </span>
                                    <span>
                                        Address
                                    </span>
                                </p>
                                <p class="has-text-weight-bold text-softblue ml-1">
                                    {{ $user->address ?? 'N/A' }}
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </x-content.footer>
        </div>
    </div>
@endsection
