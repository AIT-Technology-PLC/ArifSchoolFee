@extends('layouts.app')

@section('title', 'Features')

@section('content')
    <div class="columns is-marginless is-multiline">
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Enabled"
                box-color="bg-softblue"
                :amount="$totalEnabled"
                icon="fas fa-check"
            />
        </div>
        <div class="column is-6 p-lr-0">
            <x-common.total-model
                model="Disabled"
                box-color="bg-green"
                :amount="$totalDisabled"
                icon="fas fa-ban"
            />
        </div>
    </div>

    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Features
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-cubes" />
                        <span>
                            {{ number_format($features->count()) }} {{ str()->plural('feature', $features->count()) }}
                        </span>
                    </span>
                </h1>
            </x-slot>
        </x-content.header>
        <x-content.footer>
            <x-common.client-datatable length-menu="[10]">
                <x-slot name="headings">
                    <th><abbr> # </abbr></th>
                    <th><abbr> Name </abbr></th>
                    <th><abbr> Status </abbr></th>
                    <th><abbr> Action </abbr></th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($features as $feature)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td> {{ $feature->name }} </td>
                            <td>
                                @if ($feature->isEnabled())
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
                                    :route="route('admin.features.toggle', $feature->id)"
                                    action="{{ $feature->isEnabled() ? 'disable' : 'enable' }}"
                                    intention="{{ $feature->isEnabled() ? 'disable' : 'enable' }} this feature"
                                    icon="fas fa-toggle-{{ $feature->isEnabled() ? 'off' : 'on' }}"
                                    label="{{ $feature->isEnabled() ? 'Disable' : 'Enable' }}"
                                    data-title="{{ $feature->isEnabled() ? 'Disable' : 'Enable' }}"
                                    class="btn-softblue is-outlined has-text-weight-medium is-small px-2 py-0"
                                />
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
