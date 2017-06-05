<div class="item">
    <div class="ui {{ $colorHeader }} header"><i class="list layout icon"></i> {{ trans('strings.meta_menu_common') }}</div>
    <div class="menu">
        @include('menus.items.common.links')
        @if(\App\Common\PrivilegesUtils::canGetMines())
        <a href="{{ route('mines') }}" class="{{ $routeName == 'mines' ? 'active' : '' }} item">{{ trans('strings.menu_mines') }}</a>
        @endif
        @if(\App\Common\PrivilegesUtils::canGetBookmarks())
        <a href="{{ route('bookmarks') }}" class="{{ $routeName == 'bookmarks' ? 'active' : '' }} item">{{ trans('strings.menu_bookmarks') }}</a>
        @endif
    </div>
</div>