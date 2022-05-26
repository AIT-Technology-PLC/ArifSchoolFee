@extends('layouts.app')

@section('title', 'Sale Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-hashtag"
                        :data="$sale->code ?? 'N/A'"
                        label="Receipt No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-credit-card"
                        :data="$sale->payment_type ?? 'N/A'"
                        label="Payment Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$sale->customer->company_name ?? 'N/A'"
                        label="Customer"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$sale->sold_on->toFormattedDateString() ?? 'N/A'"
                        label="Sold On"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($sale->subtotalPrice, 2)"
                        label="SubTotal Price ({{ userCompany()->currency }})"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-dollar-sign"
                        :data="number_format($sale->grandTotalPrice, 2)"
                        label=" Grand Total Price ({{ userCompany()->currency }})"
                    />
                </div>
                @if (!userCompany()->isDiscountBeforeVAT())
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-percentage"
                            data="{{ number_format($sale->discount * 100, 2) }}%"
                            label="Discount"
                        />
                    </div>
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-dollar-sign"
                            :data="number_format($sale->grandTotalPriceAfterDiscount, 2)"
                            label="Grand Total Price (After Discount)"
                        />
                    </div>
                @endif
                <div class="column is-12">
                    <x-common.show-data-section
                        type="long"
                        :data="is_null($sale->description) ? 'N/A' : nl2br(e($sale->description))"
                        label="Details"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            <x-common.button
                tag="a"
                href="{{ route('sales.edit', $sale->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <div class="notification bg-gold has-text-white has-text-weight-medium {{ session('message') ? '' : 'is-hidden' }}">
                <span class="icon">
                    <i class="fas fa-times-circle"></i>
                </span>
                <span>
                    {{ session('message') }}
                </span>
            </div>
            <x-common.success-message :message="session('deleted')" />
            {{ $dataTable->table() }}
            @if (isFeatureEnabled('Gdn Management'))
                <div class="box has-background-white-bis radius-bottom-0">
                    <h1 class="title is-size-5 text-green has-text-centered">
                        DO for this sale
                    </h1>
                    <div class="table-container has-background-white-bis">
                        <table class="table is-hoverable is-fullwidth is-size-7 has-background-white-bis">
                            <thead>
                                <tr>
                                    <th><abbr> # </abbr></th>
                                    <th><abbr> DO No </abbr></th>
                                    <th><abbr> Status </abbr></th>
                                    <th><abbr> Issued on </abbr></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sale->gdns as $gdn)
                                    <tr>
                                        <td> {{ $loop->index + 1 }} </td>
                                        <td class="is-capitalized">
                                            <a
                                                class="is-underlined"
                                                href="{{ route('gdns.show', $gdn->id) }}"
                                            >
                                                {{ $gdn->code }}
                                            </a>
                                        </td>
                                        <td>
                                            @if ($gdn->isSubtracted())
                                                <span class="tag is-small bg-purple has-text-white">
                                                    Subtracted from inventory
                                                </span>
                                            @else
                                                <span class="tag is-small bg-blue has-text-white">
                                                    Not subtracted from inventory
                                                </span>
                                            @endif
                                        </td>
                                        <td class="is-capitalized">
                                            {{ $gdn->issued_on->toFormattedDateString() }}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
