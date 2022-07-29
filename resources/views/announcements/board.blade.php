@extends('layouts.app')

@section('title', 'Announcement Board')

@section('content')
    <div class="container is-fluid mt-3">
        <div class="columns">
            <div class="column is-8">
                <nav class="level is-mobile">
                    <div class="level-left">
                        <div class="level-item">
                            <span class="ml-2 text-green is-size-4 has-text-weight-bold is-size-6-mobile">
                                <span><i class="fa fa-bullhorn"></i></span>
                                <span>Board</span>
                            </span>
                        </div>
                    </div>
                    <div class="level-right">
                        <p class="level-item">{{ $announcements->links() }}</p>
                    </div>
                </nav>
                @foreach ($announcements as $announcement)
                    @if ($announcement->isApproved())
                        <div class="box has-background-white">
                            <article class="media">
                                <div class="media-left column is-2">
                                    {{ $announcement->title }}
                                </div>
                                <div class="media-content column is-10">
                                    <div class="content">
                                        <strong>{{ $announcement->createdBy->name }}</strong><small> {{ $announcement->created_at->diffForHumans() }} </small><br>
                                        <p
                                            x-data='{ isCollapsed: false, maxLength: 100, originalContent: `{!! $announcement->content !!}`, content: "" }'
                                            x-init="content = originalContent.slice(0, maxLength)"
                                        >
                                            <span x-html="isCollapsed ? originalContent : content"></span>
                                            <button
                                                class="btn-green is-outlined is-borderless is-small is-rounded"
                                                x-on:click="isCollapsed = !isCollapsed"
                                                x-show="originalContent.length > maxLength"
                                                x-text="isCollapsed ? 'Show less' : '...Show more'"
                                            ></button>
                                        </p>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endif
                @endforeach
            </div>
            <div class="column is-4">
                <x-datatables.filter
                    filters="'sort'"
                    style="background: white !important"
                >
                    <div class="columns is-marginless is-vcentered">
                        <div class="column is-12 p-lr-0 pt-0">
                            <x-forms.field class="has-text-centered">
                                <x-forms.control>
                                    <x-forms.select
                                        id=""
                                        name=""
                                        class="is-size-7-mobile is-fullwidth"
                                        x-model="filters.sort"
                                        x-on:change="add('sort')"
                                    >
                                        <option
                                            disabled
                                            selected
                                            value=""
                                        >
                                            Sort
                                        </option>
                                        <option value="all"> All </option>
                                        @foreach (['Latest', 'Oldest'] as $sort)
                                            <option value="{{ str()->lower($sort) }}"> {{ $sort }} </option>
                                        @endforeach
                                    </x-forms.select>
                                </x-forms.control>
                            </x-forms.field>
                        </div>
                    </div>
                </x-datatables.filter>
            </div>
        </div>
    </div>
@endsection
