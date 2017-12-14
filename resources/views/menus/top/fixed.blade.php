<div class="ui large top fixed hidden menu">
    @include('menus.items.logos.fixed')
    @include('menus.items.toc')
    <div class="links">
        @include('menus.items.common.links')
    </div>
    <div class="right menu">
        <div class="item">
            @include('menus.items.logs.buttons')
        </div>
        <div class="item">
            <menu-filter></menu-filter>
        </div>
    </div>
</div>