<!DOCTYPE html>
@include('includes.tag')
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <link rel="canonical" href="{{ \Illuminate\Support\Facades\Request::getFacadeRoot()->url() }}" >
    <title>{{ trans('strings.view_503_title') }} - {{ config('app.name') }}</title>
    <meta name="description" content="{{ trans('strings.app_meta_description_portal') }}">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
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
                        @include('includes.rotaryKeywords.html')
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- main content -->
        <div class="ui container">
            <div class="ui grid spaced-top-2">
                <div class="ui one column grid">
                    <div class="ui huge icon info message">
                        <i class="@yield('icon') icon"></i>
                        <div class="content">
                            <div class="header">
                                <div class="ui text shape error-title">
                                    <div class="sides">
                                       @yield('titles', $initiallang_rotatry_keywords)
                                    </div>
                                </div>
                            </div>
                            <div class="ui text shape error-message">
                                <div class="sides">
                                    @yield('messages', $initiallang_rotatry_keywords)
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui centered grid spaced-top-2">
                <div class="sixteen wide mobile eight wide tablet eight wide computer column">
                    @include('includes.coq')
                </div>
            </div>

        </div>
    </div>
</div>


<!-- footer -->
@include('layouts.footer.common')
<script src="{{ mix("js/manifest.js") }}"></script>
<script src="{{ mix("js/vendor.js") }}"></script>
<script src="{{ mix("js/app.js") }}"></script>
@include('includes.rotaryKeywords.script')
@include('errors.script')
</body>
</html>

