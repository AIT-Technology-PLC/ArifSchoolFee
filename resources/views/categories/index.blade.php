@extends('layouts.app')

@section('title')
Product Categories
@endsection

@section('content')
<section class="mt-3 mx-3 m-lr-0">
    <div class="box radius-bottom-0 mb-0 has-background-white-bis">
        <h1 class="title text-green has-text-weight-medium is-size-5">
            Product Categories' List
        </h1>
    </div>
    <div class="box radius-top-0">
        <div class="table-container">
            <table class="table is-hoverable is-fullwidth is-size-7">
                <thead>
                    <tr>
                        <th><abbr> # </abbr></th>
                        <th><abbr> Category </abbr></th>
                        <th><abbr> Description </abbr></th>
                        <th><abbr> Products </abbr></th>
                        <th><abbr> Created On </abbr></th>
                        <th><abbr> Actions </abbr></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($categories as $category)
                    <tr>
                        <td> {{ $loop->index + 1  }} </td>
                        <td class="is-capitalized"> {{ $category->name }} </td>
                        <td> {{ $category->description }} </td>
                        <td class="is-capitalized"> 10 </td>
                        <td class="is-capitalized"> {{ $category->created_at->toDayDateTimeString() }} </td>
                        <td>
                            <a href="{{ route('categories.edit', $category->id) }}" title="Modify Category Data">
                                <span class="icon is-size-4 text-green">
                                    <i class="fas fa-pen-square"></i>
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
