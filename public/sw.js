importScripts('https://www.gstatic.com/firebasejs/3.6.8/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/3.6.8/firebase-messaging.js');
firebase.initializeApp({messagingSenderId: "773895683605"});
const cloudMessaging = firebase.messaging();
cloudMessaging.setBackgroundMessageHandler(function(payload) {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // Customize notification here
    const notificationTitle = 'DestockEurope';
    const notificationOptions = {
        body: 'Il y a de nouvelles annonces',
        icon: '/mstile-150x150.png',
    };

    // return self.registration.showNotification(notificationTitle,
    //     notificationOptions);
    return self.registration.showNotification();
});

let version='v3::';
self.addEventListener('install', function(e) {
    e.waitUntil(caches.open(version + 'destockEuropeCache')
        .then(function(cache) {
            return cache.addAll([
                '/',
                '/home',
                '/advert/create',
                '/js/app.40a793dba8c6262a963e.js',
                '/css/app.9b73ddb81bc251a73525.css',
                '/css/themes/basic/assets/fonts/icons.woff',
                '/css/themes/basic/assets/fonts/icons.woff2',
                '/css/themes/basic/assets/fonts/icons.ttf'
            ]);
        })
    );
});

self.addEventListener('fetch', function(event) {
    event.respondWith(
        caches.match(event.request).then(function(response) {
            return response || fetch(event.request);
        })
    );
});