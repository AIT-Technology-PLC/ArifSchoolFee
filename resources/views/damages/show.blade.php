@extends('layouts.app')

@section('title')
    Damage Details
@endsection

@section('content')
    <div class="box mt-3 mx-3 m-lr-0">
        <div class="columns is-marginless is-multiline">
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-file-invoice"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $damage->code ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Damage No
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-6">
                <div>
                    <div class="columns is-marginless is-vcentered is-mobile text-green">
                        <div class="column is-1">
                            <span class="icon is-size-3">
                                <i class="fas fa-calendar-day"></i>
                            </span>
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold">
                                {{ $damage->issued_on->toFormattedDateString() ?? 'N/A' }}
                            </div>
                            <div class="is-uppercase is-size-7">
                                Issued On
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="column is-12">
                <div>
                    <div class="columns is-marginless is-vcentered text-green">
                        <div class="column">
                            <div class="has-text-weight-bold">
                                Details
                            </div>
                            <div class="is-size-7 mt-3">
                                {!! is_null($damage->description) ? 'N/A' : nl2br(e($damage->description)) !!}
                            </div>
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
                                Damage Details
                            </h1>
                        </div>
                    </div>
                </div>
                <div class="level-right">
                    <div class="level-item is-justify-content-left">
                        <div>
                            <a href="{{ route('damages.edit', $damage->id) }}" class="button is-small bg-green has-text-white">
                                <span class="icon">
                                    <i class="fas fa-pen"></i>
                                </span>
                                <span>
                                    Edit
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="box radius-bottom-0 mb-0 radius-top-0">
            <div class="notification bg-lightpurple text-purple {{ session('failedMessage') ? '' : 'is-hidden' }}">
                @foreach ((array) session('failedMessage') as $message)
                    <span class="icon">
                        <i class="fas fa-times-circle"></i>
                    </span>
                    <span>
                        {{ $message }}
                    </span>
                    <br>
                @endforeach
            </div>
            <div class="notification bg-green has-text-white has-text-weight-medium {{ session('successMessage') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-check-circle"></i>
                </span>
                <span>
                    {{ session('successMessage') }}
                </span>
            </div>
            @if ($damage->isApproved() && $damage->isSubtracted())
                <div class="box is-shadowless bg-lightgreen has-text-left mb-6">
                    <p class="has-text-grey text-green is-size-6">
                        <span class="icon">
                            <i class="fas fa-check-circle"></i>
                        </span>
                        <span>
                            Products have been subtracted from inventory.
                        </span>
                    </p>
                </div>
            @endif
            @if ($damage->isApproved() && !$damage->isSubtracted())
                @can('Subtract Damage')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            Product(s) listed below are still not subtracted from your inventory.
                            <br>
                            Click on the button below to subtract product(s) from the inventory.
                        </p>
                        <form id="formOne" action="{{ route('damages.subtract', $damage->id) }}" method="post" novalidate>
                            @csrf
                            <button id="openCloseSaleModal" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-minus-circle"></i>
                                </span>
                                <span>
                                    Subtract from inventory
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-6">
                            <span class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <span>
                                Product(s) listed below are still not subtracted from your inventory.
                            </span>
                        </p>
                    </div>
                @endcan
            @endif
            @if (!$damage->isApproved())
                @can('Approve Damage')
                    <div class="box has-background-white-ter has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-7">
                            This Damage has not been approved.
                            <br>
                            Click on the button below to approve this Damage.
                        </p>
                        <form id="formOne" action="{{ route('damages.approve', $damage->id) }}" method="post" novalidate>
                            @csrf
                            <button id="openApproveGdnModal" class="button bg-purple has-text-white mt-5 is-size-7-mobile">
                                <span class="icon">
                                    <i class="fas fa-signature"></i>
                                </span>
                                <span>
                                    Approve Damage
                                </span>
                            </button>
                        </form>
                    </div>
                @else
                    <div class="box is-shadowless bg-lightpurple has-text-left mb-6">
                        <p class="has-text-grey text-purple is-size-6">
                            <span class="icon">
                                <i class="fas fa-exclamation-circle"></i>
                            </span>
                            <span>
                                This Damage has not been approved.
                            </span>
                        </p>
                    </div>
                @endcan
            @endif
            <div class="table-container">
                <table class="table is-hoverable is-fullwidth is-size-7">
                    <thead>
                        <tr>
                            <th><abbr> # </abbr></th>
                            <th><abbr> From </abbr></th>
                            <th><abbr> Product </abbr></th>
                            <th><abbr> Quantity </abbr></th>
                            <th><abbr> Unit Price </abbr></th>
                            <th><abbr> Description </abbr></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($damage->damageDetails as $damageDetail)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td class="is-capitalized">
                                    {{ $damageDetail->warehouse->name }}
                                </td>
                                <td class="is-capitalized">
                                    {{ $damageDetail->product->name }}
                                </td>
                                <td>
                                    {{ number_format($damageDetail->quantity, 2) }}
                                    {{ $damageDetail->product->unit_of_measurement }}
                                </td>
                                <td>
                                    {{ $damage->company->currency }}.
                                    {{ number_format($damageDetail->unit_price, 2) }}
                                </td>
                                <td>
                                    {!! nl2br(e($damageDetail->description)) !!}
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </section>
@endsection
