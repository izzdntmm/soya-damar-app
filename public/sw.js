self.addEventListener('push', function (event) {
    const data = event.data.json();

    const title = data.title || 'Notifikasi';
    const options = {
        body: data.body,
        icon: '/icon.png',
        data: data.url ? { url: data.url } : {},
    };

    event.waitUntil(
        self.registration.showNotification(title, options)
    );
});

self.addEventListener('notificationclick', function (event) {
    event.notification.close();
    if (event.notification.data && event.notification.data.url) {
        event.waitUntil(clients.openWindow(event.notification.data.url));
    }
});
