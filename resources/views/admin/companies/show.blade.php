@extends('layouts.app')

@section('title', $company->name)

@section('content')
    <x-common.content-wrapper>
        <x-content.header
            title="General Information"
            is-mobile
        >
            <x-common.dropdown name="Actions">
                @can('Manage Admin Panel Companies')
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('admin.companies.edit', $company->id) }}"
                            mode="button"
                            icon="fas fa-pen"
                            label="Edit"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="button"
                            mode="button"
                            @click="$dispatch('open-company-limits-modal')"
                            icon="fas fa-diagram-project"
                            label="Manage Resources"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="button"
                            mode="button"
                            @click="$dispatch('open-company-integrations-modal')"
                            icon="fas fa-code"
                            label="Manage Integrations"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="button"
                            mode="button"
                            @click="$dispatch('open-company-features-modal')"
                            icon="fas fa-cubes"
                            label="Manage Features"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endcan
                @can('Manage Admin Panel Subscriptions')
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="button"
                            mode="button"
                            @click="$dispatch('open-company-subscriptions-modal')"
                            icon="fas fa-file-contract"
                            label="Create Subscription"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endcan
                @can('Manage Admin Panel Pads')
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('admin.companies.pads.create', $company) }}"
                            mode="button"
                            icon="fas fa-folder-open"
                            label="Create Pad"
                            class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endcan
                @if ($company->isInTraining())
                    @can('Manage Admin Panel Resets')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="button"
                                mode="button"
                                @click="$dispatch('open-company-reset-modal')"
                                icon="fas fa-power-off"
                                label="Reset Account"
                                class="has-text-weight-medium is-small text-green is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @can('Manage Admin Panel Companies')
                    <hr class="navbar-divider">
                    <x-common.dropdown-item>
                        <x-common.transaction-button
                            :route="route('admin.companies.toggle_activation', $company->id)"
                            action="{{ $company->isEnabled() ? 'deactivate' : 'activate' }}"
                            intention="{{ $company->isEnabled() ? 'deactivate' : 'activate' }} this company account"
                            icon="fas {{ $company->isEnabled() ? 'fa-times-circle' : 'fa-check-circle' }}"
                            label="{{ $company->isEnabled() ? 'Deactivate' : 'Activate' }}"
                            class="has-text-weight-bold is-small {{ $company->isEnabled() ? 'text-purple' : 'text-green' }} is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endcan
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <div class="columns is-marginless is-multiline">
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-bank"
                        :data="$company->name ?? 'N/A'"
                        label="Company Name"
                    />
                </div>
                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-tag"
                        :data="$company->plan->name ?? 'N/A'"
                        label="Plan"
                    />
                </div>

                <div class="column is-6">
                    <x-common.show-data-section
                        icon="fas fa-calendar"
                        :data="$company->created_at->toFormattedDateString()"
                        label="Registration Date"
                    />
                </div>
                <div class="column is-6">
                    <div class="columns is-marginless is-vcentered is-mobile {{ $company->isEnabled() ? 'text-green' : 'text-purple' }}">
                        <div class="column is-1">
                            <x-common.icon
                                name="fas {{ $company->isEnabled() ? 'fa-check-circle' : 'fa-times-circle' }}"
                                class="is-size-3"
                            />
                        </div>
                        <div class="column m-lr-20">
                            <div class="is-size- has-text-weight-bold"> {{ $company->isEnabled() ? 'Active' : 'Deactivated' }} </div>
                            <div class="is-uppercase is-size-7"> Status </div>
                        </div>
                    </div>
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-diagram-project"></i>
                        </span>
                        <span>Resources</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu="[5]"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Resource </abbr></th>
                        <th class="has-text-right"><abbr> Amount </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($companyLimits as $companyLimit => $amount)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ str()->title($companyLimit) }} </td>
                                <td class="has-text-right"> {{ number_format($amount, 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>

        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-code"></i>
                        </span>
                        <span>Integrations</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu="[5]"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Integration </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($company->integrations as $integration)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ str()->title($integration->name) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>

        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-cubes"></i>
                        </span>
                        <span>Features By Plan</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu="[5]"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Feature </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($planFeatures as $planFeature)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ str()->title($planFeature->name) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>

        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-cubes"></i>
                        </span>
                        <span>Additional Features</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu="[5]"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Feature </abbr></th>
                        <th><abbr> Status </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($companyFeatures as $companyFeature)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ str()->title($companyFeature->name) }} </td>
                                <td> {{ $companyFeature->pivot->is_enabled ? 'Enabled' : 'Disabled' }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>

        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-folder-open"></i>
                        </span>
                        <span>Pads</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu="[5]"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Pads </abbr></th>
                        <th><abbr> Inventory </abbr></th>
                        <th><abbr> Status </abbr></th>
                        <th><abbr> Actions </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($company->pads as $pad)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ str()->title($pad->name) }} </td>
                                <td> {{ str()->title($pad->getInventoryOperationType()) }} </td>
                                <td> {{ $pad->isEnabled() ? 'Enabled' : 'Disabled' }} </td>
                                <td>
                                    <x-common.button
                                        tag="a"
                                        href="{{ route('admin.pads.show', $pad->id) }}"
                                        mode="button"
                                        label="View"
                                        class="btn-green is-outlined has-text-weight-medium is-not-underlined is-small px-2 py-0 is-transparent-color"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>

        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-rectangle-list"></i>
                        </span>
                        <span>Custom Fields</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu="[5]"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Field </abbr></th>
                        <th><abbr> Feature </abbr></th>
                        <th><abbr> Status </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($company->customFields as $customField)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ str()->title($customField->label) }} </td>
                                <td> {{ str()->title($customField->model_type) }} </td>
                                <td> {{ $customField->isActive() ? 'Enabled' : 'Disabled' }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>

        <div class="column is-6 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-green has-text-weight-medium is-size-6">
                        <span class="icon mr-1">
                            <i class="fas fa-file-contract"></i>
                        </span>
                        <span>Subscriptions</span>
                    </h1>
                </x-slot:header>
            </x-content.header>
            <x-content.footer>
                <x-common.client-datatable
                    has-filter="false"
                    has-length-change="false"
                    paging-type="simple"
                    length-menu="[5]"
                >
                    <x-slot name="headings">
                        <th><abbr> # </abbr></th>
                        <th><abbr> Starting Date </abbr></th>
                        <th><abbr> Expiry Date </abbr></th>
                        <th><abbr> Days Left </abbr></th>
                        <th><abbr> Actions </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($company->subscriptions as $subscription)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ $subscription->starts_on->toFormattedDateString() }} </td>
                                <td> {{ $subscription->expiresOn->toFormattedDateString() }} </td>
                                <td> {{ $subscription->expiresOn->diffInDays(today()) }} </td>
                                <td>
                                    <x-common.action-buttons
                                        :buttons="['edit', 'delete']"
                                        model="admin.subscriptions"
                                        :id="$subscription->id"
                                    />
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>

    @include('admin.limits.edit', ['company' => $company])

    @include('admin.integrations.edit', ['company' => $company])

    @include('admin.features.edit', ['company' => $company])

    @include('admin.companies.partials.reset', ['company' => $company])

    @include('admin.subscriptions.create', ['company' => $company])
@endsection
