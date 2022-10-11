@extends('layouts.app')

@section('title', 'Price Increment')

@section('content')
    <x-common.content-wrapper>
        <x-content.header
            title="General Information"
            is-mobile
        >
            @if (!$priceIncrement->isSpecificProducts())
                <x-common.dropdown name="Actions">
                    @if ($priceIncrement->isUploadExcel())
                        @can('Import Price Increment')
                            <x-common.dropdown-item>
                                <x-common.button
                                    tag="button"
                                    mode="button"
                                    @click="$dispatch('open-import-modal') "
                                    icon="fas fa-upload"
                                    label="Import Product"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endif
                    @if (!$priceIncrement->isApproved())
                        @can('Approve Price Increment')
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('price-increments.approve', $priceIncrement->id)"
                                    action="approve"
                                    intention="approve this Price Increment"
                                    icon="fas fa-signature"
                                    label="Approve"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endif
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('price-increments.edit', $priceIncrement->id) }}"
                            mode="button"
                            icon="fas fa-pen"
                            label="Edit"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                </x-common.dropdown>
            @endif
        </x-content.header>
        <x-content.footer>
            @if (!$priceIncrement->isSpecificProducts())
                <div>
                    <x-common.success-message :message="session('deleted') ?? session('imported')" />
                    <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
                    @if (!$priceIncrement->isApproved())
                        <x-common.fail-message message="This Price Increment has not been approved yet." />
                    @endif
                    @if ($priceIncrement->isApproved())
                        <x-common.success-message message="Price Increment successfully approved." />
                    @endif
                </div>
            @endif
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-tags"
                        :data="$priceIncrement->code"
                        label="Reference NO"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-tags"
                        :data="$priceIncrement->price_type"
                        label="Price Type"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-money-bill"
                        :data="$priceIncrement->price_increment"
                        label="Price Increment"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-th"
                        :data="$priceIncrement->target_product"
                        label="Target Product"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    @if ($priceIncrement->isSpecificProducts())
        <x-common.content-wrapper class="mt-5">
            <x-content.header
                title="Details"
                is-mobile
            >
                <x-common.dropdown name="Actions">
                    @if (!$priceIncrement->isApproved())
                        @can('Approve Price Increment')
                            <x-common.dropdown-item>
                                <x-common.transaction-button
                                    :route="route('price-increments.approve', $priceIncrement->id)"
                                    action="approve"
                                    intention="approve this Price Increment"
                                    icon="fas fa-signature"
                                    label="Approve"
                                    class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                                />
                            </x-common.dropdown-item>
                        @endcan
                    @endif
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('price-increments.edit', $priceIncrement->id) }}"
                            mode="button"
                            icon="fas fa-pen"
                            label="Edit"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                </x-common.dropdown>
            </x-content.header>
            <x-content.footer>
                <x-common.success-message :message="session('deleted') ?? null" />
                <x-common.fail-message :message="count($errors->all()) ? $errors->all() : null" />
                @if (!$priceIncrement->isApproved())
                    <x-common.fail-message message="This Price Increment has not been approved yet." />
                @endif
                @if ($priceIncrement->isApproved())
                    <x-common.success-message message="Price Increment successfully approved." />
                @endif
                {{ $dataTable->table() }}
            </x-content.footer>
        </x-common.content-wrapper>
    @endif

    @can('Import Price Increment')
        <x-common.import
            title="Import Products"
            action="{{ route('price-increments.import', $priceIncrement->id) }}"
        />
    @endcan
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
