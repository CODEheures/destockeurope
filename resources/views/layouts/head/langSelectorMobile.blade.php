@if(!auth()->check() && \App\Common\RouteUtils::routeHaveLangParam())
    <langs-dropdown
            list-available-lang="{{ json_encode(config('codeheuresUtils.availableLocales')) }}"
            size="mini"
            color="blue"
    ></langs-dropdown>
@endif