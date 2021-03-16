@extends('layouts.app')

@section('title')
    Products Prices
@endsection

@section('content')
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                Price List
            </h1>
        </div>
        <div class="box radius-top-0">
            <div>
                <table id="table_id" class="is-hoverable is-size-7 display" data-date="[3]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th id="firstTarget"><abbr> # </abbr></th>
                            <th class="text-green"><abbr> Product </abbr></th>
                            <th class="has-text-right text-purple"><abbr> Min Price </abbr></th>
                            <th class="has-text-right"><abbr> Last Modified</abbr></th>
                            <th><abbr> Added By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prices as $price)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized"> 
                                    <span class="tag bg-green has-text-white">
                                        {{ $price->product->name }} 
                                    </span>
                                </td>
                                <td class="has-text-right"> 
                                    <span class="tag bg-purple has-text-white">
                                        {{ $price->price }} 
                                    </span>
                                </td>
                                <td class="has-text-right"> {{ $price->updated_at->toFormattedDateString() }} </td>
                                <td> {{ $price->createdBy->name ?? 'N/A' }} </td>
                                <td> {{ $price->updatedBy->name ?? 'N/A' }} </td>
                                <td>
                                    <a href="{{ route('prices.edit', $price->id) }}" data-title="Modify Price Data">
                                        <span class="tag is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-pen-square"></i>
                                            </span>
                                            <span>
                                                Edit
                                            </span>
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
