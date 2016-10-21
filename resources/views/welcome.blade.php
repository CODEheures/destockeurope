@extends('layouts.app')

@section('content')
    <welcome
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            route-meta-category="{{ route('metaCategory.index') }}"
            categories-dropdown-menu-first-menu-name="{{ trans('strings.menu_category') }}"
            filter-ribbon="{{ trans('strings.view_filter_ribbon') }}"
            filter-price-title ="{{ trans('strings.view_filter_price_title') }}"
            route-get-adverts-list="{{ route('advert.index') }}"
            advert-title-label="{{ trans('strings.view_advert_form_title_label') }}"
            advert-description-label="{{ trans('strings.view_advert_form_description_label') }}"
            advert-price-label="{{ trans('strings.view_advert_form_price_label') }}"
            see-advert-link-label="{{ trans('strings.view_advert_by_link_label') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}">
    </welcome>
@endsection
