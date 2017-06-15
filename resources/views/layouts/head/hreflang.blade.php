@if(!auth()->check() && \App\Common\RouteUtils::routeHaveLangParam())
    @foreach(config('codeheuresUtils.availableLocales') as $lang)
        <link rel="alternate" hreflang="{{ $lang }}" href="{{ route(\Illuminate\Support\Facades\Route::currentRouteName(), array_merge(\Illuminate\Support\Facades\Route::current()->parameters, ['lang'=>$lang])) }}"/>
    @endforeach
@endif