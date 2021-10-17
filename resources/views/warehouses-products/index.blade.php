@extends('layouts.app')

@section('title')
    History of {{ $product->name }} in {{ $warehouse->name }}
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                History of {{ $product->name }} in {{ $warehouse->name }}
            </h1>
        </div>
        <div class="box radius-top-0">
            <x-common.success-message :message="session('deleted')" />
            <div>
                <table class="regular-datatable is-hoverable is-size-7 display nowrap" data-date="[1]" data-numeric="[4,6]">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="has-text-right"><abbr> Date </abbr></th>
                            <th><abbr> Type </abbr></th>
                            <th><abbr> Ref N<u>o</u> </abbr></th>
                            <th class="has-text-right"><abbr> Quantity </abbr></th>
                            <th><abbr> Description </abbr></th>
                            <th class="has-text-right"><abbr> Balance </abbr></th>
                        </tr>
                    </thead>
                    <tbody class="list">
                        @foreach ($history as $item)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="has-text-right"> {{ $item['date']->toFormattedDateString() }} </td>
                                <td>
                                    <span class="tag is-small bg-purple has-text-white">
                                        {{ $item['type'] }}
                                    </span>
                                </td>
                                <td> {{ $item['code'] }} </td>
                                <td class="has-text-right">
                                    <span class="tag is-small btn-green is-outline">
                                        <span class="icon is-medium">
                                            <i class="fas fa-{{ $item['function'] == 'add' ? 'plus' : 'minus' }}-circle"></i>
                                        </span>
                                        {{ $item['quantity'] }}
                                        {{ $item['unit_of_measurement'] }}
                                    </span>
                                </td>
                                <td> {{ $item['details'] }} </td>
                                <td class="has-text-right">
                                    <span class="tag is-small bg-green has-text-white">
                                        {{ $item['balance'] }}
                                        {{ $item['unit_of_measurement'] }}
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
