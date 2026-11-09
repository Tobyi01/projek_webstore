const CACHE_NAME = 'nesia-pwa-v1';
const CORE_ASSETS = [
    '/offline-pos.html',
    '/manifest.json',
    '/js/offline-store.js',
    '/js/offline-pos.js',
    '/assets/img/favicon.png',
    '/assets/img/pwa-192.png',
    '/assets/img/pwa-512.png',
];

self.addEventListener('install', event => {
    event.waitUntil(caches.open(CACHE_NAME).then(cache => cache.addAll(CORE_ASSETS)));
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(keys => Promise.all(
            keys.filter(key => key.startsWith('nesia-pwa-') && key !== CACHE_NAME)
                .map(key => caches.delete(key))
        ))
    );
    self.clients.claim();
});

self.addEventListener('fetch', event => {
    const request = event.request;
    const url = new URL(request.url);
    if (request.method !== 'GET' || url.origin !== self.location.origin) return;

    if (CORE_ASSETS.includes(url.pathname)) {
        event.respondWith(caches.match(request).then(cached => cached || fetch(request)));
        return;
    }

    if (request.mode === 'navigate') {
        event.respondWith(fetch(request).catch(() => caches.match('/offline-pos.html')));
    }
});
