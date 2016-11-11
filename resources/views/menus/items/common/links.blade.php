<a href="{{ route('home') }}" class="{{ $routeName == 'home' ? 'active' : '' }} item">{{ trans('strings.menu_home') }}</a>
<a href="{{ route('advert.create') }}" class="{{ $routeName == 'advert.create' ? 'active' : '' }} item">{{ trans('strings.menu_offer') }}</a>
{{--<a class="item">{{ trans('strings.menu_sells') }}</a>--}}
{{--<a class="item">{{ trans('strings.menu_research') }}</a>--}}