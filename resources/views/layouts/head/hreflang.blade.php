@if(\Illuminate\Support\Facades\Route::currentRouteName() && \Illuminate\Support\Facades\Route::current())
    @if(\App\Common\RouteUtils::routeHaveLangParam())
    <link rel="canonical" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(\Illuminate\Support\Facades\Route::current()->parameters, ['lang'=>null], config('runtime.query'))) }}" >
    @else
    <link rel="canonical" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(\Illuminate\Support\Facades\Route::current()->parameters, [], config('runtime.query'))) }}" >
    @endif
    @if(!auth()->check() && \App\Common\RouteUtils::routeHaveLangParam())
        <link rel="alternate" hreflang="x-default" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(\Illuminate\Support\Facades\Route::current()->parameters, ['lang'=>null], config('runtime.query'))) }}" />
        @foreach(config('codeheuresUtils.availableLocales') as $lang)
            <link rel="alternate" hreflang="{{ $lang }}" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(\Illuminate\Support\Facades\Route::current()->parameters, ['lang'=>$lang], config('runtime.query'))) }}"/>
        @endforeach
    @endif
@endif