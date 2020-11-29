@extends('layouts.app')

@section('title')
    Employee Profile - {{ $employee->user->name }}
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Employee Profile - {{ $employee->user->name }}
            </h1>
        </div>
        <div class="box radius-top-0">
            <div class="columns is-marginless is-centered">
                <div class="column is-3">
                    <figure class="image is-square">
                        <img class="is-rounded" src="{{ asset('img/nabil.jpg') }}">
                    </figure>
                </div>
            </div>
            <div class="columns is-marginless is-centered">
                <div class="column is-6 has-text-centered">
                    <div class="box bg-lightgreen">
                        <div>
                            <h1 class="title is-size-7 has-text-grey-light is-uppercase">
                                <span class="icon">
                                    <i class="fas fa-user"></i>
                                </span>
                                <span>
                                    Name
                                </span>
                            </h1>
                            <h2 class="subtitle text-green has-text-weight-medium">
                                {{ $employee->user->name }}
                            </h2>
                        </div>
                        <div class="mt-5">
                            <h1 class="title is-size-7 has-text-grey-light is-uppercase">
                                <span class="icon">
                                    <i class="fas fa-at"></i>
                                </span>
                                <span>
                                    Email
                                </span>
                            </h1>
                            <h2 class="subtitle text-green has-text-weight-medium">
                                {{ $employee->user->email }}
                            </h2>
                        </div>
                        <div class="mt-5">
                            <h1 class="title is-size-7 has-text-grey-light is-uppercase">
                                <span class="icon">
                                    <i class="fas fa-user-tie"></i>
                                </span>
                                <span>
                                    Position
                                </span>
                            </h1>
                            <h2 class="subtitle text-green has-text-weight-medium">
                                {{ $employee->position ?? 'Not Assigned' }}
                            </h2>
                        </div>
                        <div class="mt-5">
                            <h1 class="title is-size-7 has-text-grey-light is-uppercase">
                                <span class="icon">
                                    <i class="fas fa-user-cog"></i>
                                </span>
                                <span>
                                    Role
                                </span>
                            </h1>
                            <h2 class="subtitle text-green has-text-weight-medium">
                                {{ $employee->permission->role }}
                            </h2>
                        </div>
                        <div class="mt-5">
                            <h1 class="title is-size-7 has-text-grey-light is-uppercase">
                                <span class="icon">
                                    <i class="fas fa-lock"></i>
                                </span>
                                <span>
                                    System Access
                                </span>
                            </h1>
                            <h2 class="subtitle text-green has-text-weight-medium">
                                {{ $employee->enabled ? 'Enabled' : 'Blocked' }}
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
