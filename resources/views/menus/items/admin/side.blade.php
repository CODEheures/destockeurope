@if(auth()->check() && auth()->user()->role == 'admin')
    <div class="item">
        <div class="ui {{ $colorHeader }} header"><i class="settings icon"></i> {{ trans('strings.meta_menu_admin') }}</div>
        <div class="menu">
            <a href="{{ route('category.index') }}" class="{{ $routeName == 'category.index' ? 'active' : '' }} item">{{ trans('strings.menu_category') }}</a>
        </div>
    </div>
@endif