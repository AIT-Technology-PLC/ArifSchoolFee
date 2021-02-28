importScripts(
    "https://storage.googleapis.com/workbox-cdn/releases/6.0.2/workbox-sw.js"
);

const { precacheAndRoute, matchPrecache } = workbox.precaching;
const { skipWaiting, clientsClaim, setCacheNameDetails } = workbox.core;
const { NetworkFirst, NetworkOnly, CacheFirst } = workbox.strategies;
const { registerRoute, setCatchHandler } = workbox.routing;

skipWaiting();
clientsClaim();

const PRECACHE = "precache-v3";
const RUNTIME = "runtime-v3";

setCacheNameDetails({
    prefix: "",
    suffix: "",
    precache: PRECACHE,
    runtime: RUNTIME,
});

precacheAndRoute([
    { url: "/offline", revision: 6 },
    { url: "/manifest.json", revision: null },
    { url: "/img/favicon.png", revision: null },
    { url: "/img/logo.png", revision: null },
    { url: "/pwa/pwa-192x192.png", revision: null },
    { url: "/pwa/pwa-512x512.png", revision: null },
    { url: "/js/caller.js", revision: 6 },
    { url: "/js/app.js", revision: 6 },
    { url: "/css/app.css", revision: 6 },
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
        url: "https://cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css",
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
        url: "https://cdn.jsdelivr.net/npm/chart.js@2.8.0",
        revision: null,
    },
    {
        url: "https://cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js",
        revision: null,
    },
    {
        url: "https://cdn.datatables.net/plug-ins/1.10.22/sorting/natural.js",
        revision: null,
    },
    {
        url:
            "https://cdnjs.cloudflare.com/ajax/libs/sweetalert/2.1.2/sweetalert.min.js",
        revision: null,
    },
    {
        url: "https://cdnjs.cloudflare.com/ajax/libs/pace/1.2.3/pace.min.js",
        revision: null,
    },
]);

registerRoute(
    ({ request }) => request.destination === "font",
    new CacheFirst({
        cacheName: PRECACHE,
    })
);

registerRoute(
    ({ request }) => request.method === "POST",
    new NetworkOnly(),
    "POST"
);

registerRoute(({ request, url }) => {
    if (
        request.mode == "navigate" &&
        !url.pathname.includes("create") &&
        !url.pathname.includes("edit") &&
        !url.pathname.includes("login")
    ) {
        return true;
    }
}, new NetworkFirst());

registerRoute(({ request, url }) => {
    if (
        request.mode == "navigate" &&
        (url.pathname.includes("create") ||
            url.pathname.includes("edit") ||
            url.pathname.includes("login"))
    ) {
        return true;
    }
}, new NetworkOnly());

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
