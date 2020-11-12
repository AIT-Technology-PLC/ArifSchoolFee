@extends('layouts.app')

@section('title')
Employee Management
@endsection

@section('content')
<div class="columns is-marginless">
    <div class="column is-4">
        <div class="box text-green">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-users"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        {{ $totalEmployees }}
                    </div>
                    <div class="is-uppercase is-size-7">
                        Total Employees
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-4">
        <div class="box text-blue">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-user-check"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        {{ $totalEnabledEmployees }}
                    </div>
                    <div class="is-uppercase is-size-7">
                        Enabled Employees
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="column is-4">
        <div class="box text-purple">
            <div class="columns is-marginless is-vcentered is-mobile">
                <div class="column has-text-centered is-paddingless">
                    <span class="icon is-large is-size-1">
                        <i class="fas fa-user-alt-slash"></i>
                    </span>
                </div>
                <div class="column is-paddingless">
                    <div class="is-size-3 has-text-weight-bold">
                        {{ $totalBlockedEmployees }}
                    </div>
                    <div class="is-uppercase is-size-7">
                        Blocked Employees
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<section class="mt-3 mx-3 m-lr-0">
    <div class="box radius-bottom-0 mb-0 has-background-white-bis">
        <h1 class="title text-green has-text-weight-medium is-size-5">
            Employee Account Management
        </h1>
    </div>
    <div class="box radius-top-0">
        <div class="table-container">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th><abbr> Name </abbr></th>
                        <th><abbr> Email </abbr></th>
                        <th><abbr> Position </abbr></th>
                        <th><abbr> Enabled </abbr></th>
                        <th><abbr> Last Login </abbr></th>
                        <th><abbr> Added On </abbr></th>
                        <th><abbr> Actions </abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($employees as $employee)
                    <tr>
                        <td> {{ $loop->index + 1  }} </td>
                        <td class="is-capitalized"> {{ $employee->user->name }} </td>
                        <td> {{ $employee->user->email }} </td>
                        <td class="is-capitalized">
                            @if ($employee->position)
                            {{$employee->position }}
                            @else
                            <span class="has-text-grey">
                                Not Assigned
                            </span>
                            @endif
                        </td>
                        <td>
                            @if ($employee->enabled)
                            <span class="tag bg-blue has-text-white">
                                <span class="icon">
                                    <i class="fas fa-user-check"></i>
                                </span>
                                <span>
                                    Enabled
                                </span>
                            </span>
                            @else
                            <span class="tag bg-purple has-text-white">
                                <span class="icon">
                                    <i class="fas fa-user-alt-slash"></i>
                                </span>
                                <span>
                                    Blocked
                                </span>
                            </span>
                            @endif
                        </td>
                        <td>
                            {{ $employee->user->last_online_at ? $employee->user->last_online_at->diffForHumans() : 'New User' }}
                        </td>
                        <td> {{ $employee->user->created_at->toFormattedDateString() }} </td>
                        <td>
                            <a href="{{ route('employees.edit', $employee->id) }}" title="Modify Employee Data">
                                <span class="icon is-size-5 is-medium text-green">
                                    <i class="fas fa-pen-square"></i>
                                </span>
                            </a>
                            <a href="" title="Modify Permissions">
                                <span class="icon is-size-5 is-medium text-gold">
                                    <i class="fas fa-lock-open"></i>
                                </span>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>
@endsection
