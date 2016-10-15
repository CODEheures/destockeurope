<div class="item">
    <div class="ui {{ $colorHeader }} header"><i class="list layout icon"></i> {{ trans('strings.meta_menu_common') }}</div>
    <div class="menu">
        <a href="{{ route('home') }}" class="{{ $routeName == 'home' ? 'active' : '' }} item">{{ trans('strings.menu_home') }}</a>
        <a class="item">{{ trans('strings.menu_sells') }}</a>
        <a class="item">{{ trans('strings.menu_research') }}</a>
        <a href="{{ route('advert.create') }}" class="{{ $routeName == 'advert.create' ? 'active' : '' }} item">{{ trans('strings.menu_offer') }}</a>
    </div>
</div>