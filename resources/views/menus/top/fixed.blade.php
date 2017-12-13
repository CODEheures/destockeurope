<div class="ui large top fixed hidden menu">
    @include('menus.items.logos.fixed')
    @include('menus.items.toc')
    <div class="links">
        @include('menus.items.common.links')
    </div>
    <div class="right item">
        @include('storeSetter.contents.menu-filter')
        <menu-filter></menu-filter>
        @include('menus.items.logs.buttons')
    </div>
</div>