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
                        icon="fas fa-font"
                        :data="$pad->abbreviation"
                        label="Abbreviation"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-icons"
                        :data="$pad->icon"
                        label="Icon"
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
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-print"
                        data="{{ str()->ucfirst($pad->print_orientation) }} ({{ str()->ucfirst($pad->print_paper_size) }})"
                        label="Paper Orientation & Size"
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
        <x-content.header
            title="Details"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                <x-common.dropdown-item>
                    <x-common.button
                        tag="a"
                        href="{{ route('pads.edit', $pad->id) }}"
                        mode="button"
                        icon="fas fa-pen"
                        label="Edit"
                        class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                    />
                </x-common.dropdown-item>
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage') ?? session('deleted')" />
            {{ $dataTable->table() }}
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Statuses" />
        <x-content.footer>
            <x-common.bulma-table>
                <x-slot name="headings">
                    <th> # </th>
                    <th> Name </th>
                    <th> Colors </th>
                    <th class="has-text-centered"> Active </th>
                    <th class="has-text-centered"> Editable </th>
                    <th class="has-text-centered"> Deletable </th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($pad->padStatuses as $padStatus)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized">{{ $padStatus->name }}</td>
                            <td class="is-capitalized">
                                <span
                                    class="tag has-text-weight-bold"
                                    style="background: {{ $padStatus->bg_color }} !important;color: {{ $padStatus->text_color }} !important"
                                >
                                    {{ $padStatus->name }}
                                </span>
                            </td>
                            <td class="has-text-centered">
                                @if ($padStatus->isActive())
                                    <span class="icon text-green">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @else
                                    <span class="icon text-purple">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="has-text-centered">
                                @if ($padStatus->isEditable())
                                    <span class="icon text-green">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @else
                                    <span class="icon text-purple">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                @endif
                            </td>
                            <td class="has-text-centered">
                                @if ($padStatus->isDeletable())
                                    <span class="icon text-green">
                                        <i class="fas fa-check-circle"></i>
                                    </span>
                                @else
                                    <span class="icon text-purple">
                                        <i class="fas fa-times-circle"></i>
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.bulma-table>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Permissions" />
        <x-content.footer>
            <x-common.bulma-table>
                <x-slot name="headings">
                    <th> # </th>
                    <th class="has-text-centered"> Name </th>
                </x-slot>
                <x-slot name="body">
                    @foreach ($pad->padPermissions as $padPermission)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td class="is-capitalized has-text-centered">
                                {{ $padPermission->name }}
                            </td>
                        </tr>
                    @endforeach
                </x-slot>
            </x-common.bulma-table>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Convert To" />
        <x-content.footer>
            <x-common.bulma-table>
                <x-slot name="headings">
                    <th> # </th>
                    <th> Feature </th>
                </x-slot>
                <x-slot name="body">
                    @forelse ($pad->convert_to as $feature)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td>
                                {{ str($feature)->title()->singular() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="2"
                                class="has-text-centered"
                            >
                                No converts found
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-common.bulma-table>
        </x-content.footer>
    </x-common.content-wrapper>

    <x-common.content-wrapper class="mt-5">
        <x-content.header title="Convert From" />
        <x-content.footer>
            <x-common.bulma-table>
                <x-slot name="headings">
                    <th> # </th>
                    <th> Feature </th>
                </x-slot>
                <x-slot name="body">
                    @forelse ($pad->convert_from as $feature)
                        <tr>
                            <td> {{ $loop->index + 1 }} </td>
                            <td>
                                {{ str($feature)->title()->singular() }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td
                                colspan="2"
                                class="has-text-centered"
                            >
                                No converts found
                            </td>
                        </tr>
                    @endforelse
                </x-slot>
            </x-common.bulma-table>
        </x-content.footer>
    </x-common.content-wrapper>
@endsection

@push('scripts')
    {{ $dataTable->scripts() }}
@endpush
