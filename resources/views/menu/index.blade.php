@extends('layouts.app')

@section('title', 'Main Menu')

@section('content')
    <x-common.content-wrapper>
        @if (isFeatureEnabled('User Management', 'General Settings', 'Custom Field Management'))
            @canany(['Read Employee', 'Update Company', 'Read Custom Field'])
                <section>
                    <x-content.header>
                        <x-slot name="header">
                            <span class="icon">
                                <i class="fas fa-cog"></i>
                            </span>
                            <span class="ml-2">
                                Settings
                            </span>
                        </x-slot>
                    </x-content.header>
                    <x-content.footer>
                        <div class="columns is-marginless is-multiline is-mobile">
                            @if (isFeatureEnabled('Custom Field Management'))
                                @can('Read Custom Field')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('custom-fields.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-table"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Custom Fields
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('User Management') && !isFeatureEnabled('Employee Management'))
                                @can('Read Employee')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('employees.index') }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-users"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Employees
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @if (isFeatureEnabled('General Settings'))
                                @can('Update Company')
                                    <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                        <a
                                            href="{{ route('companies.edit', userCompany()->id) }}"
                                            class="general-menu-item button text-green bg-lightgreen is-borderless"
                                        >
                                            <span class="icon is-size-5">
                                                <i class="fas fa-building"></i>
                                            </span>
                                        </a>
                                        <br>
                                        <span class="is-size-6 is-size-7-mobile text-green">
                                            Company Profile
                                        </span>
                                    </div>
                                @endcan
                            @endif

                            @foreach (pads('General Settings') as $pad)
                                @canpad('Read', $pad)
                                <div class="column is-3-tablet is-6-mobile has-text-centered has-text-grey">
                                    <a
                                        href="{{ route('pads.transactions.index', $pad->id) }}"
                                        class="general-menu-item button text-green bg-lightgreen is-borderless"
                                    >
                                        <span class="icon is-size-5">
                                            <i class="{{ $pad->icon }}"></i>
                                        </span>
                                    </a>
                                    <br>
                                    <span class="is-size-6 is-size-7-mobile text-green">
                                        {{ $pad->abbreviation }}
                                    </span>
                                </div>
                                @endcanpad
                            @endforeach
                        </div>
                    </x-content.footer>
                </section>
            @endcanany
        @endif
    </x-common.content-wrapper>
@endsection
