<hr class="navbar-divider">
<div
    x-data="notification"
    class="navbar-item"
>
    <button
        x-bind:disabled="pushButtonDisabled || loading"
        type="button"
        class="button is-small is-borderless is-size-6-5"
        :class="{ 'text-green': !isPushEnabled, 'text-purple': isPushEnabled }"
        @click="togglePush"
    >
        <span
            class="icon"
            :class="{ 'text-green': !isPushEnabled, 'text-purple': isPushEnabled }"
        >
            <i :class="{ 'fa-solid fa-bell': !isPushEnabled, 'fa-solid fa-bell-slash': isPushEnabled }"></i>
        </span>
        <span x-text="isPushEnabled ? 'Disable Notification' : 'Enable Notification'"></span>
    </button>
</div>


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
