importScripts(
    "https://storage.googleapis.com/workbox-cdn/releases/6.0.2/workbox-sw.js"
);

const { precacheAndRoute, matchPrecache } = workbox.precaching;
const { skipWaiting, clientsClaim, setCacheNameDetails } = workbox.core;
const { NetworkFirst, NetworkOnly, CacheFirst } = workbox.strategies;
const { registerRoute, setCatchHandler } = workbox.routing;

skipWaiting();
clientsClaim();

const VERSION = 120;
const PRECACHE = "precache-v5";
const RUNTIME = "runtime-v5";

setCacheNameDetails({
    prefix: "",
    suffix: "",
    precache: PRECACHE,
    runtime: RUNTIME,
});

precacheAndRoute(
    [
        { url: "/offline", revision: VERSION },
        { url: "/manifest.json", revision: null },
        { url: "/img/favicon.png", revision: null },
        { url: "/img/logo.webp", revision: null },
        { url: "/pwa/pwa-192x192.png", revision: null },
        { url: "/pwa/pwa-512x512.png", revision: null },
        { url: "/js/caller.js", revision: VERSION },
        { url: "/js/template.js", revision: VERSION },
        { url: "/js/app.js", revision: VERSION },
        { url: "/js/store.js", revision: VERSION },
        { url: "/js/datatables-plugins.js", revision: VERSION },
        { url: "/livewire/livewire.js", revision: VERSION },
        { url: "/css/bulma-select2.css", revision: VERSION },
        { url: "/css/app.css", revision: VERSION },
        {
            url: "https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css",
            revision: null,
        },
        {
            url: "https://cdn.rawgit.com/octoshrimpy/bulma-o-steps/master/bulma-steps.css",
            revision: null,
        },
        {
            url: "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css",
            revision: null,
        },
        {
            url: "https://cdn.datatables.net/1.10.24/css/jquery.dataTables.min.css",
            revision: null,
        },
        {
            url: "https://cdn.datatables.net/buttons/1.7.0/css/buttons.dataTables.min.css",
            revision: null,
        },
        {
            url: "https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css",
            revision: null,
        },
        {
            url: "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css",
            revision: null,
        },
        {
            url: "https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css",
            revision: null,
        },
        {
            url: "https://cdn.jsdelivr.net/npm/bulma-quickview@2.0.0/dist/css/bulma-quickview.min.css",
            revision: null,
        },
        {
            url: "https://fonts.cdnfonts.com/css/swera-demo",
            revision: null,
        },
        {
            url: "https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js",
            revision: null,
        },
        {
            url: "https://cdn.jsdelivr.net/npm/axios@0.21.0/dist/axios.min.js",
            revision: null,
        },
        {
            url: "https://unpkg.com/alpinejs@3.4.2/dist/cdn.min.js",
            revision: null,
        },
        {
            url: "https://unpkg.com/@alpinejs/collapse@3.4.2/dist/cdn.min.js",
            revision: null,
        },
        {
            url: "https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js",
            revision: null,
        },
        {
            url: "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js",
            revision: null,
        },
        {
            url: "https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js",
            revision: null,
        },
        {
            url: "https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js",
            revision: null,
        },
        {
            url: "https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js",
            revision: null,
        },
        {
            url: "https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.3/pace.min.js",
            revision: null,
        },
        {
            url: "https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js",
            revision: null,
        },
        {
            url: "https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js",
            revision: null,
        },
    ],
    {
        ignoreURLParametersMatching: [/.*/],
    }
);

registerRoute(
    ({ request }) => request.destination === "font",
    new CacheFirst({
        cacheName: PRECACHE,
    })
);

registerRoute(
    ({ request }) => request.method !== "GET",
    new NetworkOnly(),
    "POST"
);

registerRoute(({ request }) => request.mode === "navigate", new NetworkFirst());

const handler = async (options) => {
    const dest = options.request.destination;

    if (dest === "document") {
        return (await matchPrecache("offline")) || Response.error();
    }

    return Response.error();
};

setCatchHandler(handler);

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => cacheName !== PRECACHE)
                    .filter((cacheName) => cacheName !== RUNTIME)
                    .map((cacheName) => caches.delete(cacheName))
            );
        })
    );
});

(() => {
    "use strict";

    const WebPush = {
        init() {
            self.addEventListener("push", this.notificationPush.bind(this));
            self.addEventListener("notificationclick", this.notificationClick);
        },

        notificationPush(event) {
            if (
                !(
                    self.Notification &&
                    self.Notification.permission === "granted"
                )
            ) {
                return;
            }

            if (event.data) {
                event.waitUntil(this.sendNotification(event.data.json()));
            }
        },

        notificationClick(event) {
            event.notification.close();

            event.waitUntil(
                clients
                    .matchAll({
                        type: "window",
                    })
                    .then((clientList) => {
                        for (const client of clientList) {
                            if ("focus" in client) {
                                client.navigate(
                                    event.notification.actions[0].action || "/"
                                );
                                client.focus();
                                break;
                            }
                        }
                        if (clients.openWindow)
                            return clients.openWindow(
                                event.notification.actions[0].action || "/"
                            );
                    })
            );
        },

        sendNotification(data) {
            return self.registration.showNotification(data.title, data);
        },
    };

    WebPush.init();
})();
