@extends('layouts.app')

@section('title', 'Pad Details')

@section('content')
    <x-common.content-wrapper>
        <x-content.header title="General Information" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-book"
                        :data="$pad->name"
                        label="Name"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-cog"
                        :data="str()->ucfirst($pad->inventory_operation_type)"
                        label="Inventory Operation"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-th"
                        :data="$pad->module"
                        label="Module"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="far {{ $pad->is_enabled ? 'fa-check-square' : 'fa-square' }}"
                        :data="$pad->is_enabled ? 'Yes' : 'No'"
                        label="Enabled"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper>
        <x-content.header title="Actions Allowed" />
        <x-content.footer>
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="far {{ $pad->is_approvable ? 'fa-check-square' : 'fa-square' }}"
                        :data="$pad->is_approvable ? 'Yes' : 'No'"
                        label="Approvable"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="far {{ $pad->is_closable ? 'fa-check-square' : 'fa-square' }}"
                        :data="$pad->is_closable ? 'Yes' : 'No'"
                        label="Closable"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="far {{ $pad->is_cancellable ? 'fa-check-square' : 'fa-square' }}"
                        :data="$pad->is_cancellable ? 'Yes' : 'No'"
                        label="Cancellable"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="far {{ $pad->is_printable ? 'fa-check-square' : 'fa-square' }}"
                        :data="$pad->is_printable ? 'Yes' : 'No'"
                        label="Printable"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="far {{ $pad->has_prices ? 'fa-check-square' : 'fa-square' }}"
                        :data="$pad->has_prices ? 'Yes' : 'No'"
                        label="Has Prices"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Details">
            <x-common.button
                tag="a"
                href="{{ route('pads.edit', $pad->id) }}"
                mode="button"
                icon="fas fa-pen"
                label="Edit"
                class="is-small bg-green has-text-white"
            />
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
        </x-content.footer>
    </x-common.content-wrapper>
@endsection
