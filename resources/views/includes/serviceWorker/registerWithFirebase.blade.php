<script>
    if ('serviceWorker' in navigator) {
        navigator.serviceWorker.register('/sw.js?v=16').then(function(reg) {
            if(reg.installing) {} else if(reg.waiting) {} else if(reg.active) {}
            firebase.initializeApp(destockShareVar.firebase.config);
            window.cloudMessaging = firebase.messaging();
            cloudMessaging.useServiceWorker(reg);
            cloudMessaging.requestPermission().then(function () {
                //console.log('have permission');
                return cloudMessaging.getToken();
            }).then(function (token) {
                destockShareVar.firebase.token=token;
            }).catch(function (err) {
                //console.log('error messaging firebase', err);
            });
//                cloudMessaging.onMessage(function (payload) {
//                    console.log('onMessage', payload);
//                })
        });
    } else {}
</script>