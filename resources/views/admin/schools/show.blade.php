@extends('layouts.app')

@section('title', $school->name)

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
                            href="{{ route('admin.schools.edit', $school->id) }}"
                            mode="button"
                            icon="fas fa-pen"
                            label="Edit"
                            class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="button"
                            mode="button"
                            @click="$dispatch('open-school-limits-modal')"
                            icon="fas fa-diagram-project"
                            label="Manage Resources"
                            class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="button"
                            mode="button"
                            @click="$dispatch('open-company-features-modal')"
                            icon="fas fa-cubes"
                            label="Manage Features"
                            class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                    <x-common.dropdown-item>
                        <x-common.button
                            tag="a"
                            href="{{ route('admin.schools.report', $school->id) }}"
                            mode="button"
                            icon="fas fa-chart-pie"
                            label="Engagement Report"
                            class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endcan
                @if ($school->canCreateNewSubscription())
                    @can('Manage Admin Panel Subscriptions')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="button"
                                mode="button"
                                @click="$dispatch('open-company-subscriptions-modal')"
                                icon="fas fa-file-contract"
                                label="Create Subscription"
                                class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @if ($school->isInTraining())
                    @can('Manage Admin Panel Resets')
                        <x-common.dropdown-item>
                            <x-common.button
                                tag="button"
                                mode="button"
                                @click="$dispatch('open-company-reset-modal')"
                                icon="fas fa-power-off"
                                label="Reset Account"
                                class="has-text-weight-medium is-small text-softblue is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                            />
                        </x-common.dropdown-item>
                    @endcan
                @endif
                @can('Manage Admin Panel Activation')
                    <hr class="navbar-divider">
                    <x-common.dropdown-item>
                        <x-common.transaction-button
                            :route="route('admin.schools.toggle_activation', $school->id)"
                            action="{{ $school->isEnabled() ? 'deactivate' : 'activate' }}"
                            intention="{{ $school->isEnabled() ? 'deactivate' : 'activate' }} this company account"
                            icon="fas {{ $school->isEnabled() ? 'fa-times-circle' : 'fa-check-circle' }}"
                            label="{{ $school->isEnabled() ? 'Deactivate' : 'Activate' }}"
                            class="has-text-weight-bold is-small {{ $school->isEnabled() ? 'text-purple' : 'text-softblue' }} is-borderless is-transparent-color is-block is-fullwidth has-text-left"
                        />
                    </x-common.dropdown-item>
                @endcan
            </x-common.dropdown>
        </x-content.header>
        <x-content.footer>
            <x-common.success-message :message="session('successMessage')" />
            <x-common.fail-message :message="session('failedMessage')" />
            <div class="columns is-marginless is-multiline is-mobile">
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-heading"
                        :data="$school->name ?? 'N/A'"
                        label="School Name"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fa-brands fa-autoprefixer"
                        :data="$school->company_code ?? 'N/A'"
                        label="School Code"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-sort"
                        :data="$school->schoolType->name ?? 'N/A'"
                        label="School Type"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-tag"
                        :data="str()->ucfirst($school->plan->name) ?? 'N/A'"
                        label="Subscription Plan"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-phone"
                        :data="$school->phone ?? 'N/A'"
                        label="Phone"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-envelope"
                        :data="$school->email ?? 'N/A'"
                        label="Email"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-location-dot"
                        :data="$school->address ?? 'N/A'"
                        label="Address"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas {{ $school->isEnabled() ? 'fa-check-circle' : 'fa-times-circle' }}"
                        data="{{ $school->isEnabled() ? 'Active' : 'Deactivated' }}"
                        label="Status"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-calendar"
                        :data="$school->created_at->toFormattedDateString()"
                        label="Registration Date"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-calendar"
                        :data="$school->subscription_expires_on?->toFormattedDateString() ?? 'Not Set'"
                        label="Subscription Expiry Date"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-calendar"
                        data="{{ (int) today()->diffInDays($school->subscription_expires_on, false) }} Days"
                        label="Subscription Expiry Days Left"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-power-off"
                        :data="$school->enabled_commission_setting == 1 ? 'Enabled' : 'Disabled'"
                        label="Commission Charge"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-user"
                        :data="ucfirst($school->charge_from)"
                        label="Charge From"
                    />
                </div>
                <div class="column is-6-mobile is-6-tablet is-4-desktop">
                    <x-common.show-data-section
                        icon="fas fa-money-bill"
                        :data="ucfirst($school->charge_type) . ' | ' . number_format($school->charge_amount, 2)"
                        label="Charge Type | Amount"
                    />
                </div>
            </div>
        </x-content.footer>
    </x-common.content-wrapper>

    <div class="columns is-marginless is-multiline mt-3">
        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-softblue has-text-weight-medium is-size-6">
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
                        <th><abbr> Amount </abbr></th>
                    </x-slot>
                    <x-slot name="body">
                        @foreach ($schoolLimits as $schoolLimit => $amount)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ str()->title($schoolLimit) }} </td>
                                <td> {{ number_format($amount, 2) }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>

        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-softblue has-text-weight-medium is-size-6">
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
                        @foreach ($school->subscriptions as $subscription)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td>
                                    @if (!is_null($subscription->starts_on))
                                        {{ $subscription->starts_on->toFormattedDateString() }}
                                    @elseif(!is_null($school->subscription_expires_on))
                                        {{ $school->subscription_expires_on->toFormattedDateString() }}
                                    @else
                                        {{ today()->toFormattedDateString() }}
                                    @endif
                                </td>
                                <td>
                                    @if (!is_null($subscription->expiresOn))
                                        {{ $subscription->expiresOn->toFormattedDateString() }}
                                    @elseif(!is_null($school->subscription_expires_on))
                                        {{ $school->subscription_expires_on->addMonths($subscription->months)->toFormattedDateString() }}
                                    @else
                                        {{ today()->addMonths($subscription->months)->toFormattedDateString() }}
                                    @endif
                                </td>
                                <td>
                                    @if ($subscription->isExpired())
                                        Expired
                                    @elseif(!is_null($subscription->expiresOn))
                                        {{ today()->diffInDays($subscription->expiresOn, false) }}
                                    @elseif(!is_null($school->subscription_expires_on))
                                        {{ $school->subscription_expires_on->diffInDays($school->subscription_expires_on->addMonths($subscription->months), false) }}
                                    @else
                                        {{ today()->diffInDays(today()->addMonths($subscription->months), false) }}
                                    @endif
                                </td>
                                <td>
                                    @if (!$subscription->isApproved())
                                        @can('Manage Admin Panel Subscriptions')
                                            @can('Manage Admin Panel Subscriptions')
                                                <x-common.action-buttons
                                                    :buttons="['edit', 'delete']"
                                                    model="admin.subscriptions"
                                                    :id="$subscription->id"
                                                />
                                            @endcan
                                            <x-common.transaction-button
                                                :route="route('admin.subscriptions.approve', $subscription->id)"
                                                action="approve"
                                                intention="approve this subscription"
                                                icon="fas fa-file-contract"
                                                data-title="Approve"
                                                class="has-text-weight-medium is-small text-softblue px-2 py-0 is-transparent-color"
                                            />
                                        @endcan
                                    @else
                                        No actions
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>

        <div class="column is-12 p-lr-0">
            <x-content.header bg-color="has-background-white">
                <x-slot:header>
                    <h1 class="title text-softblue has-text-weight-medium is-size-6">
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
                        @foreach ($schoolFeatures as $schoolFeature)
                            <tr>
                                <td> {{ $loop->index + 1 }} </td>
                                <td> {{ str()->title($schoolFeature->name) }} </td>
                                <td> {{ $schoolFeature->pivot->is_enabled ? 'Enabled' : 'Disabled' }} </td>
                            </tr>
                        @endforeach
                    </x-slot>
                </x-common.client-datatable>
            </x-content.footer>
        </div>
    </div>

    @can('Manage Admin Panel Companies')
        @include('admin.limits.edit')
        @include('admin.features.edit')
    @endcan

    @if ($school->canCreateNewSubscription())
        @can('Manage Admin Panel Subscriptions')
            @include('admin.subscriptions.create')
        @endcan
    @endif

    @if ($school->isInTraining())
        @can('Manage Admin Panel Resets')
            @include('admin.schools.partials.reset')
        @endcan
    @endif
@endsection
