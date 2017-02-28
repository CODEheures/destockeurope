<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix("css/vendor.css") }}">
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
    <script>
        window.destockShareVar={
            'serviceWorkerScope': '/sw.js',
            'vueJsDevTool': <?php echo (bool)(env('APP_DEBUG')) ? 'true':'false' ?>,
            'csrfToken': '{{ csrf_token() }}',
        };
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register(destockShareVar.serviceWorkerScope).then(function(reg) {
                if(reg.installing) {} else if(reg.waiting) {} else if(reg.active) {}
            });
        } else {}
    </script>
</head>
<body class="portal yellowbg">

    <div class="ui vertical segment header">
        <div class="rubalise"></div>
        <div class="ui container">
            <div class="item">
                <div class="ui grid">
                    <div class="six wide mobile four wide tablet four wide computer column">
                        <img src="{{ asset('images/logo.svg') }}" class="ui big image">
                    </div>
                    <div class="ten wide mobile twelve wide tablet twelve wide computer bottom aligned column keywords">
                        <h1>
                            <span>{{ trans('strings.view_portal_list_header_1') }}</span>
                            <span>{{ trans('strings.view_portal_list_header_2') }}</span>
                            <span>{{ trans('strings.view_portal_list_header_3') }}</span>
                            <span>{{ trans('strings.view_portal_list_header_4') }}</span>
                            <span>{{ trans('strings.view_portal_list_header_5') }}</span>
                            <span>{{ trans('strings.view_portal_list_header_6') }}</span>
                        </h1>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container" id="app">
        <!-- main content -->
        <div class="ui container">
            <div class="content row">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- footer -->
    @include('layouts.footer.common')
    <!-- Scripts -->
    <script src="{{ mix("js/manifest.js") }}"></script>
    <script src="{{ mix("js/vendor.js") }}"></script>
    <script src="{{ mix("js/app.js") }}"></script>
    @yield('scripts')
</body>
</html>
