<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @yield('opengraph')
    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
    @yield('css')


    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#dca300">
    <meta name="apple-mobile-web-app-title" content="DestockEurope">
    <meta name="application-name" content="DestockEurope">
    <meta name="theme-color" content="#dca300">
    <!-- Scripts -->
    <script src="https://www.gstatic.com/firebasejs/3.6.8/firebase.js"></script>
    <script>
         window.destockShareVar={
            'serviceWorkerScope': '/sw.js',
            'vueJsDevTool': {{ env('APP_DEBUG') }},
            'csrfToken': '{{ csrf_token() }}',
            'firebase': {
                'config': {
                    messagingSenderId: '{{ env('GOOGLE_FIREBASE_MESSAGINGSENDERID') }}'
                },
                'token': undefined
            }
        };
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register(destockShareVar.serviceWorkerScope).then(function(reg) {
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
    @yield('headscripts')
</head>
<body class="yellowbg">
    <?php $routeName =  \Illuminate\Support\Facades\Route::getFacadeRoot()->current()->getName() ?>
    <?php $colorHeader = 'inverted' ?>
    <!-- Side menu -->
    @include('menus.side.main')

    <div class="pusher stackable container" id="app">
        <!-- fixed menu bar -->
        @include('menus.top.fixed')
        <!-- Menu BAR -->
        @include('menus.top.master')

        <!-- main content -->
        <masterads
            is-active="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && isset($masterAdsControllerFlag) ? $masterAdsControllerFlag : false }}"
            url-img="{{config('runtime.urlMasterAds')}}"
            url-redirect="{{ config('runtime.urlLinkMasterAds') }}"
            width="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && isset($masterAdsControllerFlag) ? config('runtime.widthUrlMasterAds') : 0 }}">
        </masterads>
        <div class="ui main container" style="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && (isset($masterAdsControllerFlag) ? $masterAdsControllerFlag : false) ? 'margin-top:' . config('runtime.offsetYMasterAds').'px;' : null}}">
            <!-- Erreurs -->
            <div class="one column row">
                <div class="column">
                    @include('messages.flash')
                </div>
            </div>
            <div class="content row">
                @yield('content')
            </div>
        </div>
        <!-- footer -->
        @include('layouts.footer.common')
    </div>


    <!-- Scripts -->
    <script src="{{ mix("js/app.js") }}"></script>
    @yield('scripts')
</body>
</html>
