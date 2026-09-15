/* ═════════════════════════════════════════════════════════════════════
   Vayu — SERVICE WORKER & WEB PUSH NOTIFICATIONS
   ═════════════════════════════════════════════════════════════════════ */

const CACHE_NAME = 'ttt-pwa-cache-v1';

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

// ── 1. Listen for Push Events from Web Push Server ──
self.addEventListener('push', (event) => {
    let data = {
        title: 'Vayu',
        body: 'New drops & exclusive offers are live!',
        icon: '/assets/images/logo-icon.png',
        badge: '/assets/images/badge-icon.png',
        url: '/',
        image: null,
    };

    if (event.data) {
        try {
            const json = event.data.json();
            data = Object.assign(data, json);
        } catch (e) {
            data.body = event.data.text();
        }
    }

    const options = {
        body: data.body,
        icon: data.icon || '/TheTrendTheory.jpg',
        badge: data.badge || '/TheTrendTheory.jpg',
        image: data.image || null,
        vibrate: [100, 50, 100],
        data: {
            url: data.url || '/',
            dateOfArrival: Date.now(),
            primaryKey: 'ttt-push-' + Date.now(),
        },
        actions: [
            { action: 'open_url', title: '👉 View Now' },
            { action: 'close', title: 'Dismiss' }
        ],
        tag: 'ttt-notification-' + (data.id || Date.now()),
        renotify: true,
    };

    event.waitUntil(
        self.registration.showNotification(data.title, options)
    );
});

// ── 2. Handle Notification Click ──
self.addEventListener('notificationclick', (event) => {
    event.notification.close();

    if (event.action === 'close') {
        return;
    }

    const targetUrl = event.notification.data && event.notification.data.url
        ? event.notification.data.url
        : '/';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then((windowClients) => {
            // Check if there is already a window/tab open with the target URL
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            // Otherwise open a new tab
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
