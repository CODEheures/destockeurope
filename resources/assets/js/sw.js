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

self.addEventListener('install', function(e) {
    e.waitUntil(caches.open('destockEuropeCache')
        .then(function(cache) {
            return cache.addAll([
                '/',
                '/home',
                '/advert/create',
            ]);
        })
    );
});