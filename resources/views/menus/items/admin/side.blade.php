@if(auth()->check() && auth()->user()->role == 'admin')
    <div class="item">
        <div class="ui {{ $colorHeader }} header"><i class="settings icon"></i> {{ trans('strings.meta_menu_admin') }}</div>
        <div class="menu">
            <a href="{{ route('application.dashboard') }}" class="{{ $routeName == 'application.dashboard' ? 'active' : '' }} item">{{ trans('strings.menu_dashboard') }}</a>
            <a href="{{ route('advert.toApprove') }}" class="{{ $routeName == 'advert.toApprove' ? 'active' : '' }} item">
                {{ trans('strings.menu_advert_to_approve') }}
                <?php $count = \Codeheures\LaravelUtils\Traits\Tools\Database::getCountItems('adverts',['deleted_at' => null, 'isPublish' => true, 'isValid'=>Null]); ?>
                @if($count > 0)
                    <div class="ui circular red label">{{ $count }}</div>
                @endif
            </a>
            <a href="{{ route('advert.delegations') }}" class="{{ $routeName == 'advert.delegations' ? 'active' : '' }} item">{{ trans('strings.menu_advert_delegations') }}</a>
            <a href="{{ route('application.manage') }}" class="{{ $routeName == 'application.manage' ? 'active' : '' }} item">{{ trans('strings.menu_parameters') }}</a>
            <a href="{{ route('invoice.manage') }}" class="{{ $routeName == 'invoice.manage' ? 'active' : '' }} item">{{ trans('strings.menu_invoices') }}</a>
            <a href="{{ route('category.manage') }}" class="{{ $routeName == 'category.manage' ? 'active' : '' }} item">{{ trans('strings.menu_category') }}</a>
        </div>
    </div>
@endif