<div class="item">
    <div class="ui {{ $colorHeader }} header"><i class="list layout icon"></i> {{ trans('strings.meta_menu_common') }}</div>
    <div class="menu">
        @include('menus.items.common.links')
        @if(auth()->check() && auth()->user()->role != \App\User::ROLES[\App\User::ROLE_VALIDATOR])
        <a href="{{ route('mines') }}" class="{{ $routeName == 'mines' ? 'active' : '' }} item">{{ trans('strings.menu_mines') }}</a>
        <a href="{{ route('bookmarks') }}" class="{{ $routeName == 'bookmarks' ? 'active' : '' }} item">{{ trans('strings.menu_bookmarks') }}</a>
        @endif
    </div>
</div>