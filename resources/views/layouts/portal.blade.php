<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Styles -->
    <link rel="stylesheet" href="{{ elixir("css/app.css", '') }}">
    @yield('css')
    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
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
        <masterads
            is-active="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && isset($masterAdsControllerFlag) ? $masterAdsControllerFlag : false }}"
            url-img="{{config('runtime.urlMasterAds')}}"
            url-redirect="{{ config('runtime.urlLinkMasterAds') }}"
            width="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && isset($masterAdsControllerFlag) ? config('runtime.widthUrlMasterAds') : 0 }}">
        </masterads>

        <div class="ui container" style="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && (isset($masterAdsControllerFlag) ? $masterAdsControllerFlag : false) ? 'margin-top:' . config('runtime.offsetYMasterAds').'px;' : null}}">
            <div class="content row">
                @yield('content')
            </div>
        </div>
    </div>

    <!-- footer -->
    @include('layouts.footer.common')
    <!-- Scripts -->
    <script src="{{ elixir("js/app.js", '') }}"></script>
    @yield('scripts')
</body>
</html>
