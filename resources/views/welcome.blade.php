@extends('layouts.app')

@section('content')
    <div class="ui grid">
        <div class="mobile only tablet only row">
            <div class="ui one column grid">
                <categories-dropdown-menu
                        load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                        route-meta-category="{{ route('metaCategory.index') }}"
                        first-menu-name="{{ trans('strings.menu_category') }}">
                </categories-dropdown-menu>
            </div>
        </div>
        <div class="row">
            <div class="computer only four wide column">
                <categories-vertical-menu
                        load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                        route-meta-category="{{ route('metaCategory.index') }}">
                </categories-vertical-menu>
             </div>
            <div class="sixteen wide tablet twelve wide computer column">
                <advert-filter
                    filter-ribbon="{{ trans('strings.view_filter_ribbon') }}"
                    filter-price-title ="{{ trans('strings.view_filter_price_title') }}">
                </advert-filter>
            </div>
        </div>
    </div>
@endsection
