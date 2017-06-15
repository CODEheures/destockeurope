@if(!auth()->check() && \App\Common\RouteUtils::routeHaveLangParam())
    <langs-dropdown
            list-available-lang="{{ json_encode(config('codeheuresUtils.availableLocales')) }}"
    ></langs-dropdown>
@endif