const cacheName = 'soya-damar-v1';
const assets = [
    '/',
    '/build/assets/app-DbgnAq-C.css', 
    '/build/assets/app-CUaza54F.js'   
];

self.addEventListener('install', e => {
    e.waitUntil(
        caches.open(cacheName).then(cache => {
            return cache.addAll(assets);
        })
    );
});

self.addEventListener('fetch', e => {
    e.respondWith(
        caches.match(e.request).then(cacheRes => {
            return cacheRes || fetch(e.request);
        })
    );
});

// ═══ TAMBAHAN LOGIKA WEB PUSH NOTIFICATION (PWA) ═══

self.addEventListener('push', function(event) {
    if (event.data) {
        try {
            const data = event.data.json();
            const options = {
                body: data.body,
                icon: '/icon-pwa.png', // Sesuaikan dengan jalur icon PWA Soya Damar milikmu
                badge: '/icon-badge.png', // Icon kecil di status bar HP
                vibrate: [100, 50, 100],
                data: {
                    url: data.url // Menyimpan URL redirect bawaan dari laravel
                }
            };

            event.waitUntil(
                self.registration.showNotification(data.title, options)
            );
            const channel = new BroadcastChannel('pwa_notification_channel');
            channel.postMessage({ type: 'NEW_NOTIFICATION' });
        } catch (e) {
            console.error('Gagal memproses payload push data:', e);
        }
    }
});

// Aksi ketika Notifikasi di HP di-klik oleh Admin / Sales
self.addEventListener('notificationclick', function(event) {
    event.notification.close();
    
    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(
            clients.openWindow(event.notification.data.url)
        );
    }
});