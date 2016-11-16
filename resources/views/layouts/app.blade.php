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
    <link href="/css/app.css" rel="stylesheet">

    <!-- Scripts -->
    <script>
        window.Laravel = <?php echo json_encode([
                'csrfToken' => csrf_token(),
        ]); ?>
    </script>
</head>
<body>
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
            url="{{config('runtime.urlMasterAds')}}"
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

    </div>

    <!-- footer -->
    <div class="ui black inverted vertical footer segment">
        <div class="ui center aligned container">
            <div class="ui stackable inverted grid">
                <div class="three wide column">
                    <h4 class="ui inverted header">Community</h4>
                    <div class="ui inverted link list">
                        <a class="item" href="https://www.transifex.com/organization/semantic-org/" target="_blank">Help Translate</a>
                        <a class="item" href="https://github.com/Semantic-Org/Semantic-UI/issues" target="_blank">Submit an Issue</a>
                        <a class="item" href="https://gitter.im/Semantic-Org/Semantic-UI" target="_blank">Join our Chat</a>
                        <a class="item" href="/cla.html" target="_blank">CLA</a>
                    </div>
                </div>
                <div class="three wide column">
                    <h4 class="ui inverted header">Network</h4>
                    <div class="ui inverted link list">
                        <a class="item" href="https://github.com/Semantic-Org/Semantic-UI" target="_blank">GitHub Repo</a>
                        <a class="item" href="https://forums.semantic-ui.com" target="_blank">User Forums</a>
                        <a class="item" href="http://1.semantic-ui.com">1.x Docs</a>
                        <a class="item" href="http://legacy.semantic-ui.com">0.x Docs</a>
                    </div>
                </div>
                <div class="seven wide right floated column">
                    <h4 class="ui inverted teal header">Help Preserve This Project</h4>
                    <p> Support for the continued development of Semantic UI comes directly from the community.</p>
                    <form action="https://www.paypal.com/cgi-bin/webscr" method="post" target="_top">
                        <input type="hidden" name="cmd" value="_s-xclick">
                        <input type="hidden" name="hosted_button_id" value="7ZAF2Q8DBZAQL">
                        <button type="submit" class="ui large teal button">Donate Today</button>
                    </form>
                </div>
            </div>
            <div class="ui inverted section divider"></div>
            <img src="/images/logo.png" class="ui centered mini image">
            <div class="ui horizontal inverted small divided link list">
                <a class="item" href="http://semantic-ui.mit-license.org/" target="_blank">Free &amp; Open Source (MIT)</a>
            </div>
        </div>
    </div>
    <!-- Scripts -->
    <script src="/js/app.js"></script>
    @yield('scripts')
</body>
</html>
