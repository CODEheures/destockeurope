<div class="ui vertical inverted sidebar menu left">
    @include('menus.items.logo')
    <a class="active item">{{ trans('strings.menu_home') }}</a>
    <a class="item">{{ trans('strings.menu_sells') }}</a>
    <a class="item">{{ trans('strings.menu_research') }}</a>
    <a class="item">{{ trans('strings.menu_offer') }}</a>
    @include('menus.items.logs.links')
</div>