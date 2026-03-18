const CACHE_NAME = 'arneta-pos-v1';

self.addEventListener('install', event => {
    console.log('Service worker install event!');
    self.skipWaiting();
});

self.addEventListener('activate', event => {
    console.log('Service worker activate event!');
});

self.addEventListener('fetch', event => {
    // Basic fetch handling untuk mengizinkan website dapat diinstal (PWA criteria)
    // Tidak ada full caching offline karena aplikasi POS ini butuh koneksi server live
    event.respondWith(fetch(event.request).catch(() => {
        return new Response('Aplikasi sedang offline. Mohon periksa koneksi internet Anda.', {
            headers: { 'Content-Type': 'text/plain' }
        });
    }));
});
