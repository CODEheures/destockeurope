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
    <script src="{{ elixir("js/app.js", '') }}"></script>
    @yield('scripts')
</body>
</html>
