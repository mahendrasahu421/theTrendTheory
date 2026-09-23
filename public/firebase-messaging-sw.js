/* ═════════════════════════════════════════════════════════════════════
   The Trend Theory — Firebase Cloud Messaging Service Worker
   ═════════════════════════════════════════════════════════════════════ */

importScripts('https://www.gstatic.com/firebasejs/10.13.2/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/10.13.2/firebase-messaging-compat.js');

// Initialize Firebase in Service Worker
firebase.initializeApp({
    apiKey: "AIzaSyBxewN-r_TDJfHBwuzcdIq2Bme6dyRCWVo",
    authDomain: "the-trend-theory.firebaseapp.com",
    projectId: "the-trend-theory",
    storageBucket: "the-trend-theory.firebasestorage.app",
    messagingSenderId: "664156075505",
    appId: "1:664156075505:ios:6e6b662021c3ce7eef0050"
});

const messaging = firebase.messaging();

// Handle background messages delivered via Firebase Cloud Messaging
messaging.onBackgroundMessage(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message:', payload);

    const title = (payload.notification && payload.notification.title) || 
                  (payload.data && payload.data.title) || 
                  'The Trend Theory';

    const body = (payload.notification && payload.notification.body) || 
                 (payload.data && payload.data.body) || 
                 '';

    const icon = (payload.notification && payload.notification.icon) || 
                 (payload.data && payload.data.icon) || 
                 '/TheTrendTheory.jpg';

    const image = (payload.notification && payload.notification.image) || 
                  (payload.data && payload.data.image) || 
                  null;

    const targetUrl = (payload.data && payload.data.url) || 
                      (payload.data && payload.data.click_action) || 
                      (payload.fcmOptions && payload.fcmOptions.link) || 
                      '/shop';

    const options = {
        body: body,
        icon: icon,
        badge: '/TheTrendTheory.jpg',
        image: image,
        vibrate: [100, 50, 100],
        data: {
            url: targetUrl,
            time: Date.now()
        },
        tag: 'ttt-firebase-' + Date.now(),
        renotify: true
    };

    return self.registration.showNotification(title, options);
});

// Handle notification click event
self.addEventListener('notificationclick', function(event) {
    event.notification.close();

    const targetUrl = (event.notification.data && event.notification.data.url) 
        ? event.notification.data.url 
        : '/shop';

    event.waitUntil(
        clients.matchAll({ type: 'window', includeUncontrolled: true }).then(function(windowClients) {
            for (let i = 0; i < windowClients.length; i++) {
                const client = windowClients[i];
                if (client.url === targetUrl && 'focus' in client) {
                    return client.focus();
                }
            }
            if (clients.openWindow) {
                return clients.openWindow(targetUrl);
            }
        })
    );
});
