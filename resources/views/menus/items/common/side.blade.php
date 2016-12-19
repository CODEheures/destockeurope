<div class="item">
    <div class="ui {{ $colorHeader }} header"><i class="list layout icon"></i> {{ trans('strings.meta_menu_common') }}</div>
    <div class="menu">
        @include('menus.items.common.links')
        <a href="{{ route('mines') }}" class="{{ $routeName == 'mines' ? 'active' : '' }} item">{{ trans('strings.menu_mines') }}</a>
    </div>
</div>