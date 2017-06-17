<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <link rel="canonical" href="{{ \Illuminate\Support\Facades\Request::getFacadeRoot()->url() }}" >
    @include('layouts.head.hreflang')
    <title>{{ config('app.name') }}</title>
    <meta name="description" content="{{ trans('strings.app_meta_description_portal') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix("css/vendor.css") }}">
    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
    @yield('css')
    @include('layouts.favicons.fav')
    <!-- Scripts -->
    <script>
        window.destockShareVar={
            'serviceWorkerScope': '/sw.js',
            'vueJsDevTool': <?php echo (bool)(env('APP_DEBUG')) ? 'true':'false' ?>,
            'csrfToken': '{{ csrf_token() }}',
            'isProd': <?php echo (bool)(env('APP_URL')=='https://destockeurope.com') ? 'true':'false' ?>,
        };
        if ('serviceWorker' in navigator) {
            navigator.serviceWorker.register(destockShareVar.serviceWorkerScope).then(function(reg) {
                if(reg.installing) {} else if(reg.waiting) {} else if(reg.active) {}
            });
        } else {}
    </script>
    @yield('headscripts')
</head>
<body class="portal yellowbg">

    <div id="app">
        <div class="ui vertical segment header" id="headApp">
            <div class="rubalise"></div>
            <div class="ui container">
                <div class="item">
                    <div class="ui grid">
                        <div class="six wide mobile four wide tablet four wide computer column">
                            <a href="{{ route('portal') }}"><img src="{{ asset('images/logo.svg') }}" class="ui big image"></a>
                        </div>
                        <div class="seven wide mobile ten wide tablet ten wide computer bottom aligned column keywords">
                            <h1>
                                <span>{{ trans('strings.view_portal_list_header_1') }}</span>
                                <span>{{ trans('strings.view_portal_list_header_2') }}</span>
                                <span>{{ trans('strings.view_portal_list_header_3') }}</span>
                                <span>{{ trans('strings.view_portal_list_header_4') }}</span>
                                <span>{{ trans('strings.view_portal_list_header_5') }}</span>
                                <span>{{ trans('strings.view_portal_list_header_6') }}</span>
                            </h1>
                        </div>
                        <div class="three wide mobile two wide tablet two wide computer rigth floated column">
                            @include('layouts.head.langSelector')
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="container">
            <!-- main content -->
            <div class="ui container">
                <div class="content row">
                    <!-- banner -->
                    <div class="ui grid">
                        <div class="four wide tablet only four wide computer only column"></div>
                        <div class="twelve wide computer only column">
                            <horizontal-728x90></horizontal-728x90>
                        </div>
                        <div class="twelve wide tablet only column">
                            <horizontal-468x60></horizontal-468x60>
                        </div>
                        <div class="sixteen wide mobile only column">
                            <horizontal-234x60></horizontal-234x60>
                        </div>
                    </div>
                    @yield('content')
                </div>
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
    @if(env('APP_URL')=='https://destockeurope.com')
        @include('plugins.googleAnalytic.tracking')
        @include('plugins.googleAdsense.script')
    @endif
</body>
</html>
