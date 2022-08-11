<nav class="navbar is-fixed-top is-transparent bg-green">
    <div class="container is-fluid p-lr-0">
        <div class="navbar-brand">
            <a
                class="navbar-item is-hidden-touch pl-0"
                href="/"
            >
                <img
                    src="{{ asset('img/logo.webp') }}"
                    width="120"
                    style="max-height: 70px"
                >
                <span class="has-text-white has-text-weight-light is-size-4 mb-1">
                    Smart<span class="has-text-weight-bold">Work&trade;</span>
                </span>
            </a>
            <a
                class="navbar-item is-hidden-desktop"
                href="#"
            >
                <span class="has-text-white has-text-weight-light is-size-4">
                    Smart<span class="has-text-weight-bold">Work&trade;</span>
                </span>
            </a>
            <a
                x-data
                @click="$dispatch('open-create-modal')"
                class="navbar-item has-text-white is-size-5 is-hidden-desktop to-the-right"
                data-title="Create New ..."
            >
                <span class="icon">
                    <i class="fas fa-plus"></i>
                </span>
            </a>
            <livewire:notification-counter class="navbar-item has-text-white is-size-5 is-hidden-desktop" />
            <span
                x-data="toggler"
                @click="toggle;$dispatch('side-menu-opened')"
                class="navbar-item has-text-white is-size-5 is-hidden-desktop"
            >
                <span class="icon">
                    <i
                        class="fas fa-bars"
                        :class="{ 'fa-times': !isHidden }"
                    ></i>
                </span>
            </span>
        </div>
        <div class="navbar-menu">
            <div class="navbar-end">
                <a class="navbar-item">
                    <h1 class="ml-3 has-text-white-ter has-text-weight-light is-uppercase is-size-5">
                        <span class="icon is-medium has-text-white">
                            <i class="fas fa-building"></i>
                        </span>
                        <span class="is-capitalized">
                            {{ userCompany()->name }}
                        </span>
                    </h1>
                </a>
            </div>
            <div class="navbar-end">
                <a
                    x-data="toggler(false)"
                    x-on:click="toggle;$dispatch('toggle-side-menu-on-laptop')"
                    class="navbar-item has-text-white link-text"
                    x-bind:data-title="isHidden ? 'Change to Standard Mode' : 'Change to Fullscreen Mode'"
                >
                    <span class="icon">
                        <i
                            class="fas fa-toggle-on"
                            x-bind:class="{ 'fas fa-toggle-off': isHidden, 'fas fa-toggle-on': !isHidden }"
                        ></i>
                    </span>
                </a>
                @if (isFeatureEnabled('Announcement Management'))
                    <a
                        href="{{ route('announcements.board', ['sort' => 'latest']) }}"
                        class="navbar-item has-text-white link-text"
                        data-title="Board"
                    >
                        <span class="icon">
                            <i class="fas fa-rss"></i>
                        </span>
                    </a>
                @endif
                <a
                    href="/"
                    id="mainMenuButton"
                    class="navbar-item has-text-white link-text"
                    data-title="Main Menu"
                >
                    <span class="icon">
                        <i class="fas fa-home"></i>
                    </span>
                </a>
                <a
                    x-data
                    class="navbar-item has-text-white link-text"
                    data-title="Back"
                    @click="history.back()"
                >
                    <span class="icon">
                        <i class="fas fa-arrow-left"></i>
                    </span>
                </a>
                <a
                    x-data
                    class="navbar-item has-text-white link-text"
                    data-title="Forward"
                    @click="history.forward()"
                >
                    <span class="icon">
                        <i class="fas fa-arrow-right"></i>
                    </span>
                </a>
                <a
                    x-data
                    class="navbar-item has-text-white link-text"
                    data-title="Refresh"
                    @click="location.reload()"
                >
                    <span class="icon">
                        <i class="fas fa-redo-alt"></i>
                    </span>
                </a>
                <a
                    x-data
                    @click="$dispatch('open-create-modal')"
                    class="navbar-item has-text-white link-text"
                    data-title="Create New ..."
                >
                    <span class="icon">
                        <i class="fas fa-plus"></i>
                    </span>
                </a>
                <livewire:notification-counter class="navbar-item has-text-white link-text" />
                <div class="navbar-item has-dropdown is-hoverable">
                    <a class="navbar-link is-arrowless">
                        <figure
                            class="image is-24x24"
                            style="margin: auto !important"
                        >
                            <img
                                class="is-rounded"
                                src="{{ asset('img/user.jpg') }}"
                            >
                        </figure>
                        <span class="ml-3 has-text-white is-size-7 has-text-weight-medium">
                            {{ authUser()->name }}
                        </span>
                        <span class="icon has-text-white is-size-7">
                            <i class="fas fa-angle-down"></i>
                        </span>
                    </a>
                    <div
                        class="navbar-dropdown is-boxed"
                        style="left: -68px !important"
                    >
                        <a
                            href="{{ route('employees.show', authUser()->employee->id) }}"
                            class="navbar-item text-green"
                        >
                            <span class="icon is-medium">
                                <i class="fas fa-address-card"></i>
                            </span>
                            <span>
                                My Profile
                            </span>
                        </a>
                        @if (isFeatureEnabled('Leave Management'))
                            <hr class="navbar-divider">
                            <a
                                href="{{ route('leaves.request.create') }}"
                                class="navbar-item text-green"
                            >
                                <span class="icon is-medium">
                                    <i class="fa-solid fa-umbrella-beach"></i>
                                </span>
                                <span>
                                    Request Leave
                                </span>
                            </a>
                        @endif
                        @if (isFeatureEnabled('Expense Claim'))
                            <hr class="navbar-divider">
                            <a
                                href="{{ route('expense-claims.request.create') }}"
                                class="navbar-item text-green"
                            >
                                <span class="icon is-medium">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                </span>
                                <span>
                                    Request Expense Claim
                                </span>
                            </a>
                        @endif
                        <hr class="navbar-divider">
                        <a
                            href="{{ route('password.edit') }}"
                            class="navbar-item text-green"
                        >
                            <span class="icon is-medium">
                                <i class="fas fa-lock"></i>
                            </span>
                            <span>
                                Change Password
                            </span>
                        </a>
                        <hr class="navbar-divider">
                        <div
                            x-data="notification"
                            class="navbar-item"
                        >
                            <span
                                class="icon"
                                :class="{ 'text-green': !isPushEnabled, 'text-purple': isPushEnabled }"
                            >
                                <i :class="{ 'fa-solid fa-bell': !isPushEnabled, 'fa-solid fa-bell-slash': isPushEnabled }"></i>
                            </span>
                            <button
                                x-bind:disabled="pushButtonDisabled || loading"
                                type="button"
                                class="button is-small is-borderless is-size-6-5"
                                :class="{ 'text-green': !isPushEnabled, 'text-purple': isPushEnabled }"
                                @click="togglePush"
                                x-text="isPushEnabled ? 'Disable Notification' : 'Enable Notification'"
                            >
                            </button>
                        </div>
                        <hr class="navbar-divider">
                        <form
                            class="navbar-item"
                            action="{{ route('logout') }}"
                            method="POST"
                        >
                            @csrf
                            <x-common.icon
                                name="fas fa-power-off"
                                class="is-medium text-purple"
                            />
                            <span>
                                <x-common.button
                                    tag="button"
                                    mode="button"
                                    label="Logout"
                                    class="text-purple is-borderless is-size-6-5 ml-0 pl-0"
                                >
                            </span>
                            </x-common.button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <x-common.create-menu />
        <livewire:notifications />
    </div>
</nav>

@push('scripts')
    <script>
        document.addEventListener("alpine:init", () => {
            Alpine.data('notification', () => ({
                loading: false,
                isPushEnabled: false,
                pushButtonDisabled: true,

                init() {
                    window.Laravel = {!! json_encode([
                        'user' => Auth::user(),
                        'vapidPublicKey' => config('webpush.vapid.public_key'),
                        'pusher' => [
                            'key' => config('broadcasting.connections.pusher.key'),
                            'cluster' => config('broadcasting.connections.pusher.options.cluster'),
                        ],
                    ]) !!};
                    this.registerServiceWorker()
                },

                registerServiceWorker() {
                    if (!('serviceWorker' in navigator)) {
                        console.log('Service workers aren\'t supported in this browser.')
                        return
                    }
                    navigator.serviceWorker.register('/sw.js')
                        .then(() => this.initialiseServiceWorker())
                },
                initialiseServiceWorker() {
                    if (!('showNotification' in ServiceWorkerRegistration.prototype)) {
                        console.log('Notifications aren\'t supported.')
                        return
                    }
                    if (Notification.permission === 'denied') {
                        console.log('The user has blocked notifications.')
                        return
                    }
                    if (!('PushManager' in window)) {
                        console.log('Push messaging isn\'t supported.')
                        return
                    }
                    navigator.serviceWorker.ready.then(registration => {
                        registration.pushManager.getSubscription()
                            .then(subscription => {
                                this.pushButtonDisabled = false
                                if (!subscription) {
                                    return
                                }
                                this.updateSubscription(subscription)
                                this.isPushEnabled = true
                            })
                            .catch(e => {
                                console.log('Error during getSubscription()', e)
                            })
                    })
                },

                subscribe() {
                    navigator.serviceWorker.ready.then(registration => {
                        const options = {
                            userVisibleOnly: true
                        }
                        const vapidPublicKey = window.Laravel.vapidPublicKey
                        if (vapidPublicKey) {
                            options.applicationServerKey = this.urlBase64ToUint8Array(vapidPublicKey)
                        }
                        registration.pushManager.subscribe(options)
                            .then(subscription => {
                                this.isPushEnabled = true
                                this.pushButtonDisabled = false
                                this.updateSubscription(subscription)
                            })
                            .catch(e => {
                                if (Notification.permission === 'denied') {
                                    console.log('Permission for Notifications was denied')
                                    this.pushButtonDisabled = true
                                } else {
                                    console.log('Unable to subscribe to push.', e)
                                    this.pushButtonDisabled = false
                                }
                            })
                    })
                },

                unsubscribe() {
                    navigator.serviceWorker.ready.then(registration => {
                        registration.pushManager.getSubscription().then(subscription => {
                            if (!subscription) {
                                this.isPushEnabled = false
                                this.pushButtonDisabled = false
                                return
                            }
                            subscription.unsubscribe().then(() => {
                                this.deleteSubscription(subscription)
                                this.isPushEnabled = false
                                this.pushButtonDisabled = false
                            }).catch(e => {
                                console.log('Unsubscription error: ', e)
                                this.pushButtonDisabled = false
                            })
                        }).catch(e => {
                            console.log('Error thrown while unsubscribing.', e)
                        })
                    })
                },

                togglePush() {
                    if (this.isPushEnabled) {
                        this.unsubscribe()
                    } else {
                        this.subscribe()
                    }
                },

                updateSubscription(subscription) {
                    const key = subscription.getKey('p256dh')
                    const token = subscription.getKey('auth')
                    const contentEncoding = (PushManager.supportedContentEncodings || ['aesgcm'])[0]
                    const data = {
                        endpoint: subscription.endpoint,
                        publicKey: key ? btoa(String.fromCharCode.apply(null, new Uint8Array(key))) : null,
                        authToken: token ? btoa(String.fromCharCode.apply(null, new Uint8Array(token))) : null,
                        contentEncoding
                    }
                    this.loading = true
                    axios.post('/subscriptions', data)
                        .then(() => {
                            this.loading = false
                        })
                },

                deleteSubscription(subscription) {
                    this.loading = true
                    axios.post('/subscriptions/delete', {
                            endpoint: subscription.endpoint
                        })
                        .then(() => {
                            this.loading = false
                        })
                },

                sendNotification() {
                    this.loading = true
                    axios.post('/notifications')
                        .catch(error => console.log(error))
                        .then(() => {
                            this.loading = false
                        })
                },

                urlBase64ToUint8Array(base64String) {
                    const padding = '='.repeat((4 - base64String.length % 4) % 4)
                    const base64 = (base64String + padding)
                        .replace(/-/g, '+')
                        .replace(/_/g, '/')
                    const rawData = window.atob(base64)
                    const outputArray = new Uint8Array(rawData.length)
                    for (let i = 0; i < rawData.length; ++i) {
                        outputArray[i] = rawData.charCodeAt(i)
                    }
                    return outputArray
                }
            }));
        });
    </script>
@endpush
