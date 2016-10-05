<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

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
    <!-- Side menu -->
    @include('menus.side.main')

    <div class="pusher stackable container">
        <!-- fixed menu bar -->
        @include('menus.top.fixed')
        <!-- Menu BAR -->
        @include('menus.top.master')

    <!-- main content -->
        <div class="ui main container">

            <!-- Erreurs -->
            <div class="row">
               @include('messages.flash')
            </div>
            @yield('content')
        </div>

    </div>

    <!-- Scripts -->
    <script src="/js/app.js"></script>
</body>
</html>
