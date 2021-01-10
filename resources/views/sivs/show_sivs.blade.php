@extends('layouts.app')

@section('title')
    SIVs Details
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-file"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $sivs->count() }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total SIVs
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <h1 class="title text-green has-text-weight-medium is-size-5">
                SIVs Details
            </h1>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7 has-text-centered">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th class="text-purple has-text-right"><abbr> SIV No </abbr></th>
                            <th><abbr> Added By </abbr></th>
                            <th><abbr> Edited By </abbr></th>
                            <th><abbr> Actions </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($sivs as $siv)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-uppercase has-text-right">
                                    <span class="tag bg-purple has-text-white">
                                        {{ $siv->code }}
                                    </span>
                                </td>
                                <td> {{ $siv->createdBy->name }} </td>
                                <td> {{ $siv->updatedBy->name }} </td>
                                <td>
                                    <a data-title="See items of this SIV">
                                        <span class="tag mb-3 is-white btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-expand-alt"></i>
                                            </span>
                                            <span>
                                                Expand
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('sivs.edit', ['siv' => $siv->id]) }}" data-title="Edit SIV">
                                        <span class="tag mb-3 is-white btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-file"></i>
                                            </span>
                                            <span>
                                                Edit SIV
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
