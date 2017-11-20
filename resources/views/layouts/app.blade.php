<!DOCTYPE html>
@include('includes.tag')
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    @include('layouts.head.hreflang')
    <title>@yield('titlePagePlus') - {{ config('app.name') }}</title>
    <meta name="description" content="@yield('meta-description')">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no">
    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- opengraph -->
    @yield('opengraph')
    <!-- PACE LOADER -->
    @include('includes.paceLoader.headers')
    <!-- Styles -->
    <link rel="stylesheet" href="{{ mix("css/vendor.css") }}">
    <link rel="stylesheet" href="{{ mix("css/app.css") }}">
    @yield('css')
    @include('layouts.favicons.fav')
    <!-- Scripts -->
    <script src="https://www.gstatic.com/firebasejs/3.6.8/firebase.js"></script>
    @include('includes.destockShareVar.script')
    @include('includes.serviceWorker.registerWithFirebase')
    @include('layouts.head.cookiesAndServices')
    @yield('headscripts')
</head>
<body class="yellowbg">
    @php
        $routeName = 'home';
        if(\Illuminate\Support\Facades\Route::getFacadeRoot()->current() && \Illuminate\Support\Facades\Route::getFacadeRoot()->current()->getName()){
            $routeName =  \Illuminate\Support\Facades\Route::getFacadeRoot()->current()->getName();
        }
        $colorHeader = 'inverted'
    @endphp
    <!-- Side menu -->
    @include('menus.side.main')

    <div class="pusher stackable container" id="app">
        <alert ref="alert"></alert>
        @include('storeSetter.props.global')
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
    @include('includes.structuredDatas.logoLdScript')
    @include('includes.structuredDatas.socialsLdScript')
</body>
</html>
