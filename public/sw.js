/* eslint-disable no-undef */
importScripts('https://www.gstatic.com/firebasejs/3.6.8/firebase-app.js')
importScripts('https://www.gstatic.com/firebasejs/3.6.8/firebase-messaging.js')
firebase.initializeApp({messagingSenderId: '773895683605'})
const cloudMessaging = firebase.messaging()
cloudMessaging.setBackgroundMessageHandler(function (payload) {
  // Customize notification here
  // eslint-disable-next-line no-unused-vars
  const notificationTitle = 'DestockEurope'
  // eslint-disable-next-line no-unused-vars
  const notificationOptions = {
    body: 'Il y a de nouvelles annonces',
    icon: '/android-chrome-192x192.png'
  }

  // return self.registration.showNotification(notificationTitle,
  //     notificationOptions);
  return self.registration.showNotification()
})

let version = 'v17::'
let myCache = version + 'pages'
let preCaches = [
  '/js/start.js',
  '/js/manifest.js',
  '/js/vendor.js',
  '/js/app.js',
  '/css/vendor.css',
  '/css/app.css'
]

self.addEventListener('install', function (evt) {
  console.log('The service worker is being installed.')

  evt.waitUntil(caches.open(myCache).then(function (cache) {
    cache.addAll(preCaches)
  }))
})

// Manage cache versions
self.addEventListener('activate', function activator (event) {
  // Delete old storage when activate
  event.waitUntil(
    caches.keys().then(function (keys) {
      return Promise.all(keys
        .filter(function (key) {
          return key.indexOf(version) !== 0
        })
        .map(function (key) {
          return caches.delete(key)
        })
      )
    })
  )
})

self.addEventListener('fetch', function (event) {
  /* We should only cache GET requests, and deal with the rest of method in the
   client-side, by handling failed POST,PUT,PATCH,etc. requests.
   */
  if (event.request.method !== 'GET') {
  /* If we don't block the event as shown below, then the request will go to
   the network as usual.
   */
    return
  }
  /* Similar to event.waitUntil in that it blocks the fetch event on a promise.
   Fulfillment result will be used as the response, and rejection will end in a
   HTTP response indicating failure.
   */
  event.respondWith(
    caches
    /* This method returns a promise that resolves to a cache entry matching
     the request. Once the promise is settled, we can then provide a response
     to the fetch request.
     */
      .match(event.request)
      .then(function (cached) {
        /* Even if the response is in our cache, we go to the network as well.
         This pattern is known for producing "eventually fresh" responses,
         where we return cached responses immediately, and meanwhile pull
         a network response and store that in the cache.
         Read more:
         https://ponyfoo.com/articles/progressive-networking-serviceworker
         */
        let networked = fetch(event.request)
        // We handle the network request with success and failure scenarios.
          .then(fetchedFromNetwork, unableToResolve)
          // We should catch errors on the fetchedFromNetwork handler as well.
          .catch(unableToResolve)

        /* We return the cached response immediately if there is one, and fall
         back to waiting on the network as usual.
         */
        return cached || networked

        function fetchedFromNetwork (response) {
          let cacheCopy = response.clone()
          if (
            event.request.url.indexOf('.css') !== -1 ||
            (event.request.url.indexOf('.js') !== -1 && event.request.url.indexOf('sw.js') === -1) ||
            event.request.url.indexOf('.woff') !== -1 ||
            event.request.url.indexOf('.woff2') !== -1 ||
            event.request.url.indexOf('.ttf') !== -1 ||
            event.request.url.indexOf('.jpg') !== -1 ||
            event.request.url.indexOf('.png') !== -1 ||
            event.request.url.indexOf('.svg') !== -1 ||
            event.request.url.indexOf('.json') !== -1 ||
            event.request.url.indexOf('/images/') !== -1 ||
            event.request.url.indexOf('/static') !== -1
          ) {
            caches
              .open(myCache)
              .then(function (cache) {
                cache.put(event.request, cacheCopy)
                return response
              })
          }
          else {
            return response
          }
        }

        /* When this method is called, it means we were unable to produce a response
         from either the cache or the network. This is our opportunity to produce
         a meaningful response even when all else fails. It's the last chance, so
         you probably want to display a "Service Unavailable" view or a generic
         error response.
         */
        function unableToResolve () {
          /* There's a couple of things we can do here.
           - Test the Accept header and then return one of the `offlineFundamentals`
           e.g: `return caches.match('/some/cached/image.png')`
           - You should also consider the origin. It's easier to decide what
           "unavailable" means for requests against your origins than for requests
           against a third party, such as an ad provider
           - Generate a Response programmaticaly, as shown below, and return that
           */

          // console.log('WORKER: fetch request failed in both cache and network.');

          /* Here we're creating a response programmatically. The first parameter is the
           response body, and the second one defines the options for the response.
           */
          return cached || new Response('<h1>Network is down</h1>', {
            status: 503,
            statusText: 'Service Unavailable',
            headers: new Headers({
              'Content-Type': 'text/html'
            })
          })
        }
      })
  )
})
