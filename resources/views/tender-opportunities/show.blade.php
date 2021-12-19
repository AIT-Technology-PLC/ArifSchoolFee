@extends('layouts.app')

@section('title', 'Tender Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information">
            <x-common.button
                tag="a"
                :href="route('tender-opportunities.edit', $tenderOpportunity->id)"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="btn-green is-outlined is-small"
            />
        </x-content.header>
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-comment-dollar"
                        :data="$tenderOpportunity->code ?? 'N/A'"
                        label="Ref No"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-info"
                        :data="$tenderOpportunity->tenderStatus->status ?? 'N/A'"
                        label="Status"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="$tenderOpportunity->customer->company_name ?? 'N/A'"
                        label="Customer"
                    />
                </div>
                @if ($tenderOpportunity->price)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-money-bill-wave"
                            data="{{ money($tenderOpportunity->price, $tenderOpportunity->currency) }}"
                            label="Price"
                        />
                    </div>
                @endif
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-newspaper"
                        data="{{ $tenderOpportunity->source }}"
                        label="Source"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar-day"
                        :data="$tenderOpportunity->published_on->toFormattedDateString() ?? 'N/A'"
                        label="Publishing Date"
                    />
                </div>
                @if ($tenderOpportunity->address)
                    <div class="column is-6">
                        <x-common.show-data-section
                            icon="fas fa-map-marker-alt"
                            :data="$tenderOpportunity->address"
                            label="Address"
                        />
                    </div>
                @endif
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Body" />
        <x-content.footer>
            {!! $tenderOpportunity->body !!}
        </x-content.footer>
    </x-common.content-wrapper>

    @if ($tenderOpportunity->comments)
        <x-common.content-wrapper class="mt-5">
            <x-content.header title="Comments" />
            <x-content.footer>
                {!! $tenderOpportunity->comments !!}
            </x-content.footer>
        </x-common.content-wrapper>
    @endif
@endsection
