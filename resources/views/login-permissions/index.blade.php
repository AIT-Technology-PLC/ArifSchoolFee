@extends('layouts.app')

@section('title', 'Login Permission')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 dp-lr-0">
            <x-common.total-model
                model="Enabled"
                box-color="bg-green"
                :amount="$totalEnabledEmployees"
                icon="fas fa-check"
            />
        </div>
        <div class="column is-6 dp-lr-0">
            <x-common.total-model
                model="Disabled"
                box-color="bg-purple"
                :amount="$totalBlockedEmployees"
                icon="fas fa-ban"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Login Permission
                </h1>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <x-common.client-datatable length-menu="[10]">
                <x-slot name="headings">
                    <th><abbr> # </abbr></th>
                    <th><abbr> Name </abbr></th>
                    <th><abbr> Branch </abbr></th>
                    <th><abbr> Email </abbr></th>
                    <th><abbr> Role </abbr></th>
                    <th><abbr> Status </abbr></th>
                    <th><abbr> Action </abbr></th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($employees as $employee)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td> {{ $employee->user->name }} </td>
                            <td> {{ $employee->user->warehouse->name }} </td>
                            <td> {{ $employee->user->email }} </td>
                            <td> {{ $employee->user->roles[0]->name }} </td>
                            <td>
                                @if ($employee->isEnabled())
                                    <span class="tag bg-lightgreen text-green has-text-weight-medium">
                                        <span class="icon">
                                            <i class="fas fa-dot-circle"></i>
                                        </span>
                                        <span>
                                            Enabled
                                        </span>
                                    </span>
                                @else
                                    <span class="tag bg-purple text-purple has-text-weight-medium">
                                        <span class="icon">
                                            <i class="fas fa-warning"></i>
                                        </span>
                                        <span>
                                            Disabled
                                        </span>
                                    </span>
                                @endif
                            </td>
                            <td>
                                @if (!$employee->user->hasRole('System Manager'))
                                    <x-common.transaction-button
                                        :route="route('employees.toggle', $employee->id)"
                                        action="{{ $employee->isEnabled() ? 'disable' : 'enable' }}"
                                        intention="{{ $employee->isEnabled() ? 'disable' : 'enable' }} this employee"
                                        icon="fas fa-toggle-{{ $employee->isEnabled() ? 'off' : 'on' }}"
                                        label="{{ $employee->isEnabled() ? 'Disable' : 'Enable' }}"
                                        data-title="{{ $employee->isEnabled() ? 'Disable' : 'Enable' }}"
                                        class="btn-blue is-outlined has-text-weight-medium is-small px-2 py-0"
                                    />
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
