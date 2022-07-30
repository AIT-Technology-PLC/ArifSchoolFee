@extends('layouts.app')

@section('title', 'Announcement Board')

@section('content')
    <div class="mx-3">
        <nav class="level is-mobile">
            <div class="level-left">
                <div class="level-item">
                    <span class="ml-2 text-green is-size-4 has-text-weight-bold is-size-5-mobile is-uppercase">
                        <span class="icon is-medium">
                            <i class="fa fa-bullhorn"></i>
                        </span>
                        <span>
                            Board
                        </span>
                    </span>
                </div>
            </div>
            <div class="level-right">
                <p class="level-item">{{ $announcements->links() }}</p>
            </div>
        </nav>
        <x-datatables.filter
            filters="'sort', 'period'"
            style="background: white !important"
        >
            <div class="columns is-marginless is-vcentered is-multiline">
                <div class="column is-3 p-lr-0 pt-0">
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
                <div class="column is-3 p-lr-0 pt-0">
                    <x-forms.field class="has-text-centered">
                        <x-forms.control>
                            <x-forms.select
                                id=""
                                name=""
                                class="is-size-7-mobile is-fullwidth"
                                x-model="filters.period"
                                x-on:change="add('period')"
                            >
                                <option
                                    disabled
                                    selected
                                    value=""
                                >
                                    Period
                                </option>
                                <option value="all"> All </option>
                                @foreach (['Today', 'This Week', 'This Month'] as $period)
                                    <option value="{{ str()->lower($period) }}"> {{ $period }} </option>
                                @endforeach
                            </x-forms.select>
                        </x-forms.control>
                    </x-forms.field>
                </div>
            </div>
        </x-datatables.filter>
        <div class="columns">
            <div class="column is-8">
                @forelse ($announcements as $announcement)
                    <div class="box has-background-white m-lr-0">
                        <article class="media">
                            <div
                                class="media-left is-hidden-mobile"
                                style="width: 20% !important"
                            >
                                <aside>
                                    <span class="is-size-7 text-green has-text-weight-bold">TITLE</span>
                                    <br>
                                    <span>{{ $announcement->title }}</span>
                                </aside>

                                @if ($announcement->createdBy->is($announcement->approvedBy))
                                    <aside class="mt-4">
                                        <span class="is-size-7 text-green has-text-weight-bold">POSTED & <br> APPROVED BY</span>
                                        <br>
                                        <span>{{ $announcement->createdBy->name }}</span>
                                    </aside>
                                @else
                                    <aside class="mt-4">
                                        <span class="is-size-7 text-green has-text-weight-bold">POSTED BY</span>
                                        <br>
                                        <span>{{ $announcement->createdBy->name }}</span>
                                    </aside>

                                    <aside class="mt-4">
                                        <span class="is-size-7 text-green has-text-weight-bold">APPROVED BY</span>
                                        <br>
                                        <span>{{ $announcement->approvedBy->name }}</span>
                                    </aside>
                                @endif

                                <aside class="mt-4">
                                    <span class="is-size-7 text-green has-text-weight-bold">POSTED ON</span>
                                    <br>
                                    <span>{{ $announcement->created_at->toFormattedDateString() }}</span>
                                </aside>
                            </div>
                            <div class="media-content">
                                <div class="content">
                                    <div class="has-text-weight-bold">{{ $announcement->createdBy->name }}</div>
                                    <div class="is-size-7"> {{ $announcement->created_at->diffForHumans() }} </div><br>
                                    <p x-data='contentLengthManager(`{!! str($announcement->content)->squish()->toString() !!}`)'>
                                        <span x-html="isCollapsed ? originalContent : content"></span>
                                        <x-common.button
                                            tag="button"
                                            mode="tag"
                                            class="is-pointer text-green has-text-weight-bold is-not-underlined px-2 py-2 mt-3"
                                            x-on:click="toggle"
                                            x-show="isContentLong"
                                            x-text="isCollapsed ? 'READ LESS' : 'READ MORE'"
                                        />
                                    </p>
                                </div>
                            </div>
                        </article>
                    </div>
                @empty
                    <div class="has-text-centered text-green">
                        <span class="icon">
                            <i class="fas fa"></i>
                        </span>
                        <span>
                            No annoucements are made.
                        </span>
                    </div>
                @endforelse
            </div>
            <div class="column is-4 is-hidden-mobile">
                <div
                    class="columns is-marginless is-vcentered is-multiline"
                    style="position: sticky;top: 5rem;"
                >
                    <div class="column is-12 p-lr-0 p-0">
                        <x-common.index-insight
                            :amount="$totalAnnouncementsToday"
                            border-color="#3d8660"
                            text-color="text-green"
                            label="Today"
                        />
                        <x-common.index-insight
                            :amount="$totalAnnouncementsThisWeek"
                            border-color="#3d8660"
                            text-color="text-green"
                            label="This Week"
                        />
                        <x-common.index-insight
                            :amount="$totalAnnouncementsThisMonth"
                            border-color="#3d8660"
                            text-color="text-green"
                            label="This Month"
                        />
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script type="text/javascript">
        document.addEventListener("alpine:init", () => {
            Alpine.data("contentLengthManager", (content) => ({
                isCollapsed: false,
                maxLength: 200,
                originalContent: '',
                content: '',

                init() {
                    this.originalContent = content;
                    this.content = this.originalContent.slice(0, this.maxLength) + '...';
                },
                toggle() {
                    this.isCollapsed = !this.isCollapsed;
                },
                get isContentLong() {
                    return this.originalContent.length > this.maxLength;
                }
            }));
        });
    </script>
@endpush
