@if(auth()->check() && auth()->user()->role == 'admin')
    <div class="item">
        <div class="ui {{ $colorHeader }} header"><i class="settings icon"></i> {{ trans('strings.meta_menu_admin') }}</div>
        <div class="menu">
            <a href="{{ route('category.manage') }}" class="{{ $routeName == 'category.manage' ? 'active' : '' }} item">{{ trans('strings.menu_category') }}</a>
            <a href="{{ route('advert.toApprove') }}" class="{{ $routeName == 'advert.toApprove' ? 'active' : '' }} item">
                {{ trans('strings.menu_advert_to_approve') }}
                <?php $count = \App\Common\DBUtils::getCountItems('adverts',['isPublish' => true, 'isValid'=>Null]); ?>
                @if($count > 0)
                    <div class="ui circular red label">{{ $count }}</div>
                @endif
            </a>
        </div>
    </div>
@endif