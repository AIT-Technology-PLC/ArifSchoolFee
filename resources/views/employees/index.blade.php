@extends('layouts.app')

@section('title')
Employee List - Onrica Technologies PLC
@endsection

@section('content')
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
                        <td> {{ $employee->user->name }} </td>
                        <td> {{ $employee->user->email }} </td>
                        <td>
                            @if ($employee->user->position)
                            {{$employee->user->position }}
                            @else
                            <span class="has-text-danger">
                                Not Assigned
                            </span>
                            @endif
                        </td>
                        <td>
                            @if ($employee->user->enabled)
                            <span class="icon text-green">
                                <i class="fas fa-circle"></i>
                            </span>
                            <span>
                                Enabled
                            </span>
                            @else
                            <span class="icon has-text-danger">
                                <i class="fas fa-circle"></i>
                            </span>
                            <span>
                                Blocked
                            </span>
                            @endif
                        </td>
                        <td>
                            {{ $employee->user->last_online_at ? $employee->user->last_online_at->diffForHumans() : 'New User' }}
                        </td>
                        <td> {{ $employee->user->created_at->toFormattedDateString() }} </td>
                        <td>
                            <a href="" title="Modify Employee Data">
                                <span class="icon is-size-5 is-medium text-green">
                                    <i class="fas fa-pen-square"></i>
                                </span>
                            </a>
                            <a href="" title="Modify Permissions">
                                <span class="icon is-size-5 is-medium text-purple">
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
