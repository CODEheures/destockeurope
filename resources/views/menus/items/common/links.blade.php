<a href="{{ route('main') }}" class="{{ $routeName == 'main' ? 'active' : '' }} item">{{ trans('strings.menu_home') }}</a>
@if(!auth()->check() || auth()->user()->role != \App\User::ROLES[\App\User::ROLE_VALIDATOR])
<a href="{{ route('advert.create') }}" class="{{ $routeName == 'advert.create' ? 'active' : '' }} item">{{ trans('strings.menu_offer') }}</a>
@endif