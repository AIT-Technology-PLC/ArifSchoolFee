@extends('layouts.app')

@section('title', 'Resources')

@section('content')
    <x-common.content-wrapper>
        <x-content.header>
            <x-slot name="header">
                <h1 class="title text-softblue has-text-weight-medium is-size-5">
                    Resources
                    <span class="tag bg-softblue has-text-white has-text-weight-normal ml-1 m-lr-0">
                        <x-common.icon name="fas fa-layer-group" />
                        <span>
                            {{ number_format($limits->count()) }} {{ str()->plural('resources', $limits->count()) }}
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
                    <th><abbr> Created At </abbr></th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($limits as $limit)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td> {{ str($limit->name)->headline() }} </td>
                            <td> {{ str($limit->created_at->toFormattedDateString())}} </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.client-datatable>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
