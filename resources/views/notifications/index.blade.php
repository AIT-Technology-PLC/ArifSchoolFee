@extends('layouts.app')

@section('title')
    Notifications
@endsection

@section('content')
    <div class="columns is-marginless">
        <div class="column">
            <div class="box text-green">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-bell"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $notifications->count() }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Notifications Received
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="column">
            <div class="box has-text-grey-dark">
                <div class="columns is-marginless is-vcentered is-mobile">
                    <div class="column has-text-centered is-paddingless">
                        <span class="icon is-large is-size-1">
                            <i class="fas fa-bell"></i>
                        </span>
                    </div>
                    <div class="column is-paddingless">
                        <div class="is-size-3 has-text-weight-bold">
                            {{ $unreadNotifications->count() }}
                        </div>
                        <div class="is-uppercase is-size-7">
                            Total Unseen Notifications
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <section class="mt-3 mx-3 m-lr-0">
        <div class="box radius-bottom-0 mb-0 has-background-white-bis">
            <div class="level">
                <div class="level-left">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <h1 class="title text-green has-text-weight-medium is-size-5">
                                Notifications
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <form id="formOne" action="{{ route('notifications.markAllAsRead')}}" method="POST" enctype="multipart/form-data" novalidate>
                                @csrf
                                @method('PATCH')
                                <button id="markAllNotificationsAsRead" class="button is-small bg-green has-text-white">
                                    <span class="icon">
                                        <i class="fas fa-check-double"></i>
                                    </span>
                                    <span>
                                        Mark all as read
                                    </span>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-top-0">
            @include('components.deleted_message', ['model' => 'Category'])
            <div>
                <table id="table_id" class="is-hoverable is-size-7 display nowrap" data-date="[3,4]" data-numeric="[]">
                    <thead>
                        <tr>
                            <th id="firstTarget"><abbr> # </abbr></th>
                            <th><abbr> Notification </abbr></th>
                            <th><abbr> Status </abbr></th>
                            <th class="has-text-right"><abbr> Read at </abbr></th>
                            <th class="has-text-right"><abbr> Received On </abbr></th>
                            <th><abbr> Action</abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($notifications as $notification)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $notification->data['message'] }} </td>
                                <td>
                                    @if ($notification->read())
                                        <span class="tag is-small bg-green has-text-white">
                                            Seen
                                        </span>
                                    @else
                                        <span class="tag is-small has-background-grey-dark has-text-white">
                                            Unseen
                                        </span>
                                    @endif
                                </td>
                                <td class="has-text-right"> {{ $notification->read() ? $notification->read_at->toDayDateTimeString() : 'N/A' }} </td>
                                <td class="has-text-right"> {{ $notification->created_at->toDayDateTimeString() }} </td>
                                <td>
                                    <a href="{{ $notification->data['endpoint'] }}">
                                        <span class="tag btn-purple is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-info-circle"></i>
                                            </span>
                                            <span>
                                                View
                                            </span>
                                        </span>
                                    </a>
                                    <a href="{{ route('notifications.markAsRead', $notification->id) }}">
                                        <span class="tag btn-green is-outlined is-small text-green has-text-weight-medium">
                                            <span class="icon">
                                                <i class="fas fa-check-double"></i>
                                            </span>
                                            <span>
                                                Mark as read
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
