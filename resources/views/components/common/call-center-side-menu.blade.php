<aside class="menu">
    <ul class="menu-list">
        <li>
            <div class="columns is-marginless is-mobile is-vcentered">
                <div class="column is-3-mobile is-4-tablet is-paddingless">
                    <figure class="image is-48x48 m-auto">
                        <img
                            class="is-rounded"
                            src="{{ asset('img/user.jpg') }}"
                        >
                    </figure>
                </div>
                <div class="column is-paddingless">
                    <div class="has-text-weight-bold has-text-black is-capitalized is-size-7">
                        {{ authUser()->name }}
                    </div>
                    <div class="has-text-grey is-size-7 is-capitalized">
                        {{ authUser()->user_type == 'bank' ? authUser()->bank_name : 'Call Center' }}
                    </div>
                </div>
            </div>
            <div class="buttons is-hidden-tablet ml-3 mt-5">
                <form
                    class="is-inline"
                    action="{{ route('logout') }}"
                    method="POST"
                >
                    @csrf
                    <x-common.button
                        tag="button"
                        mode="button"
                        icon="fas fa-power-off"
                        label="Logout"
                        class="btn-purple is-outlined is-small"
                    />
                </form>
                <x-common.dropdown
                    name="More"
                    class="ml-2"
                >
                    <x-common.dropdown-item>
                        <a
                            href="{{ route('password.edit') }}"
                            class="text-blue"
                        >
                            <span class="icon is-medium">
                                <i class="fas fa-lock"></i>
                            </span>
                            <span>
                                Change Password
                            </span>
                        </a>
                    </x-common.dropdown-item>
                    <x-common.dropdown-item>
                        @if (isFeatureEnabled('Push Notification'))
                            <x-common.push-notifications />
                        @endif
                    </x-common.dropdown-item>
                </x-common.dropdown>
            </div>
        </li>
    </ul>

    <hr>

    <ul
        x-data="sideMenuAccordion"
        class="menu-list mb-2"
    >
        <li>
            <x-common.button
                tag="a"
                href="{{ route('call-centers.index') }}"
                class="text-blue is-size-6-5 has-text-left"
                ::class="{ 'is-active': isAccordionActive }"
                x-init="{{ request()->routeIs('call-centers.index') ? 'activateAccordion' : '' }}"
            >
                <x-common.icon
                    name="fas fa-dashboard"
                    class="pl-1"
                />
                <span class="ml-1"> Dashboard </span>
            </x-common.button>
        </li>
    </ul>

    <ul
        x-data="sideMenuAccordion"
        class="menu-list mb-2"
    >
        <li>
            <x-common.button
                tag="a"
                href="{{ route('call-centers.collect-fees.index') }}"
                class="text-blue is-size-6-5 has-text-left"
                ::class="{ 'is-active': isAccordionActive }"
                x-init="{{ request()->routeIs('call-centers.collect-fees.index') ? 'activateAccordion' : '' }}"
            >
                <x-common.icon
                    name="fas fa-hand-holding-dollar"
                    class="pl-1"
                />
                <span class="ml-1"> Collect Fees </span>
            </x-common.button>
        </li>
    </ul>
</aside>
