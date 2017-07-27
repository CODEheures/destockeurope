<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <link rel="canonical" href="{{ \Illuminate\Support\Facades\Request::getFacadeRoot()->url() }}" >
    @include('layouts.head.hreflang')
    <title>@yield('titlePagePlus') - {{ config('app.name') }}</title>
    <meta name="description" content="{{ trans('strings.app_meta_description_portal') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- opengraph -->
    @yield('opengraph')
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix("css/vendor.css") }}">
    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
    @yield('css')
    @include('layouts.favicons.fav')
    <!-- Scripts -->
    <script src="https://www.gstatic.com/firebasejs/3.6.8/firebase.js"></script>
    <script>
         window.destockShareVar={
            'serviceWorkerScope': '/sw.js',
             'vueJsDevTool': <?php echo (bool)(env('APP_DEBUG')) ? 'true':'false' ?>,
            'csrfToken': '{{ csrf_token() }}',
            'isProd': <?php echo (bool)(env('APP_URL')=='https://destockeurope.com') ? 'true':'false' ?>,
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
        <div class="ui main container">
            <!-- Erreurs -->
            <div class="one column row">
                <div class="column">
                    @include('messages.flash')
                </div>
            </div>
            <div class="content row">
                @include('storeSetter.props.global')
                @yield('content')
            </div>
        </div>
        <!-- footer -->
        @include('layouts.footer.common')
    </div>


    <!-- Scripts -->
    <script src="{{ mix("js/manifest.js") }}"></script>
    <script src="{{ mix("js/vendor.js") }}"></script>
    <script src="{{ mix("js/app.js") }}"></script>
    @yield('scripts')
    @if(env('APP_URL')=='https://destockeurope.com')
        @include('plugins.googleAnalytic.tracking')
        @include('plugins.googleAdsense.script')
    @endif
</body>
</html>
