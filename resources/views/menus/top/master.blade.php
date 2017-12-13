<div class="ui vertical masthead center aligned segment">
    <div class="rubalise"></div>
    <div class="ui container">
        {{--<div class="ui panorama centered test ad" data-text="panorama"></div>--}}
        <div class="ui secondary  menu">
            @include('menus.items.logos.top')
            @include('menus.items.toc')
            <div class="links langSelector">
                @include('layouts.head.langSelector')
            </div>
            <div class="links">
                @include('menus.items.common.links')
            </div>
            <div class="right item">
                @include('storeSetter.contents.menu-filter')
                <menu-filter></menu-filter>
                @include('menus.items.logs.buttons')
            </div>
        </div>
    </div>
</div>