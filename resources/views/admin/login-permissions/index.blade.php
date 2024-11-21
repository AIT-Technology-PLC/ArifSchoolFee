@extends('layouts.app')

@section('title', 'Login Permission')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 dp-lr-0">
            <x-common.total-model
                model="Enabled"
                box-color="bg-softblue"
                :amount="$totalEnabledUsers"
                icon="fas fa-check"
            />
        </div>
        <div class="column is-6 dp-lr-0">
            <x-common.total-model
                model="Disabled"
                box-color="bg-red"
                :amount="$totalBlockedUsers"
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
                    <th><abbr> Email </abbr></th>
                    <th><abbr> Status </abbr></th>
                    <th><abbr> Action </abbr></th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($users as $user)
                      @if ($user->isAdmin())
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td> {{ $user->name }} </td>
                            <td> {{ $user->email }} </td>
                            <td>
                                @if ($user->isAllowed())
                                    <span class="tag bg-lightgreen text-green has-text-weight-medium">
                                        <span class="icon">
                                            <i class="fas fa-dot-circle"></i>
                                        </span>
                                        <span>
                                            Enabled
                                        </span>
                                    </span>
                                @else
                                    <span class="tag bg-purple has-text-white has-text-weight-medium">
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
                                <x-common.transaction-button
                                    :route="route('users.toggle', $user->id)"
                                    action="{{ $user->isAllowed() ? 'disable' : 'enable' }}"
                                    intention="{{ $user->isAllowed() ? 'disable' : 'enable' }} this admin"
                                    icon="fas fa-toggle-{{ $user->isAllowed() ? 'off' : 'on' }}"
                                    label="{{ $user->isAllowed() ? 'Disable' : 'Enable' }}"
                                    data-title="{{ $user->isAllowed() ? 'Disable' : 'Enable' }}"
                                    class="btn-blue is-outlined has-text-weight-medium is-small px-2 py-0"
                                />
                            </td>
                        </tr>
                      @endif
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
