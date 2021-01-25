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
            @include('components.deleted_message', ['model' => 'Employee'])
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> Name </abbr></th>
                            <th><abbr> Email </abbr></th>
                            <th class="text-green"><abbr> Job Title </abbr></th>
                            <th class="text-gold"><abbr> Role </abbr></th>
                            <th><abbr> Enabled </abbr></th>
                            <th class="has-text-right"><abbr> Last Login </abbr></th>
                            <th class="has-text-right"><abbr> Added On </abbr></th>
                            @can('delete', $employees->first())
                                <th><abbr> Added By </abbr></th>
                                <th><abbr> Edited By </abbr></th>
                            @endcan
                            <th class="has-text-centered"><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($employees as $employee)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized"> {{ $employee->user->name }} </td>
                                <td> {{ $employee->user->email }} </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-green has-text-white">
                                        {{ $employee->position }}
                                    </span>
                                </td>
                                <td class="is-capitalized">
                                    <span class="tag is-small bg-gold has-text-white">
                                        {{ $employee->permission->role }}
                                    </span>
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
                                <td class="has-text-right">
                                    {{ $employee->user->last_online_at ? $employee->user->last_online_at->diffForHumans() : 'New User' }}
                                </td>
                                <td class="has-text-right"> {{ $employee->user->created_at->toFormattedDateString() }} </td>
                                @can('delete', $employee)
                                    <td> {{ $employee->createdBy->name ?? 'N/A' }} </td>
                                    <td> {{ $employee->updatedBy->name ?? 'N/A' }} </td>
                                @endcan
                                <td>
                                    <a class="is-block" href="{{ route('employees.edit', $employee->id) }}" data-title="Modify Employee Data">
                                        <span class="tag mb-3 is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
                                        </span>
                                    </a>
                                    <span class="is-block">
                                        @include('components.delete_button', ['model' => 'users',
                                        'id' => $employee->user_id])
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
