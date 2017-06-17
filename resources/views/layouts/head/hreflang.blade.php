@if(!auth()->check() && \App\Common\RouteUtils::routeHaveLangParam())
    <link rel="alternate" hreflang="x-default" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), \Illuminate\Support\Facades\Route::current()->parameters) }}" />
    @foreach(config('codeheuresUtils.availableLocales') as $lang)
        <link rel="alternate" hreflang="{{ $lang }}" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(\Illuminate\Support\Facades\Route::current()->parameters, ['lang'=>$lang])) }}"/>
    @endforeach
@endif