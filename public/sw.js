importScripts(
    "https://storage.googleapis.com/workbox-cdn/releases/6.0.2/workbox-sw.js"
);

const { precacheAndRoute } = workbox.precaching;
const { skipWaiting, clientsClaim, setCacheNameDetails } = workbox.core;

skipWaiting();
clientsClaim();

const PRECACHE = "precache-v1";

setCacheNameDetails({
    prefix: "",
    suffix: "",
    precache: PRECACHE,
});

precacheAndRoute([
    { url: "/offline", revision: null },
    { url: "/img/favicon.png", revision: null },
    { url: "/img/logo.png", revision: null },
    { url: "/pwa/pwa-192x192.png", revision: null },
    { url: "/pwa/pwa-512x512.png", revision: null },
    { url: "/js/caller.js", revision: null },
    { url: "/js/app.js", revision: null },
    { url: "/css/app.css", revision: null },
    {
        url: "https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css",
        revision: null,
    },
    {
        url:
            "https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css",
        revision: null,
    },
    {
        url: "https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.min.js",
        revision: null,
    },
    {
        url: "https://cdnjs.cloudflare.com/ajax/libs/list.js/2.3.1/list.min.js",
        revision: null,
    },
    {
        url: "https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.3/pace.min.js",
        revision: null,
    },
]);

self.addEventListener("activate", (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames
                    .filter((cacheName) => cacheName !== PRECACHE)
                    .map((cacheName) => caches.delete(cacheName))
            );
        })
    );
});

self.addEventListener("fetch", (event) => {
    if (event.request.mode === "navigate" && !navigator.onLine) {
        event.respondWith(caches.match("offline"));
    }
});
