@extends('layouts.app')

@section('content')
    @if(config('runtime.welcomeType')=='1')
        <welcome1
                clear-storage="{{ session()->has('clear') ? true : false }}"

                load-error-message="{{ trans('strings.view_all_error_load_message') }}"

                route-category="{{ route('category.index') }}"
                categories-dropdown-menu-first-menu-name="{{ trans('strings.menu_category') }}"
                categories-all-item="{{ trans('strings.form_dropdown_all_item') }}"
                actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"

                filter-ribbon="{{ trans('strings.view_filter_ribbon') }}"
                filter-price-title="{{ trans('strings.view_filter_price_title') }}"

                route-get-adverts-list="{{ route('advert.index') }}"
                route-get-thumb="{{ route('picture.thumb', ['type' => \App\Common\PicturesManager::TYPE_FINAL_LOCAL])}}"
                ads-frenquency="{{ config('runtime.adsFrequency') }}"
                advert-title-label="{{ trans('strings.view_advert_form_title_label') }}"
                advert-description-label="{{ trans('strings.view_advert_form_description_label') }}"
                advert-price-label="{{ trans('strings.view_advert_form_price_label') }}"
                see-advert-link-label="{{ trans('strings.view_advert_by_link_label') }}"
                total-quantity-label="{{ trans('strings.form_quantity_label') }}"
                lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
                urgent-label="{{ trans('strings.view_all_urgent') }}"
                price-info-label="{{ trans('strings.view_advert_list_price_info') }}"

                page-label="{{ trans('strings.view_pagination_page_label') }}"
                page-previous-label="{{ trans('strings.view_pagination_prev_label') }}"
                page-next-label="{{ trans('strings.view_pagination_next_label') }}">
        </welcome1>
    @elseif(config('runtime.welcomeType')=='2')
        <welcome2
                clear-storage="{{ session()->has('clear') ? true : false }}"

                load-error-message="{{ trans('strings.view_all_error_load_message') }}"

                route-category="{{ route('category.index') }}"
                categories-dropdown-menu-first-menu-name="{{ trans('strings.menu_category') }}"
                categories-all-item="{{ trans('strings.form_dropdown_all_item') }}"
                actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"

                filter-ribbon="{{ trans('strings.view_filter_ribbon') }}"
                filter-price-title="{{ trans('strings.view_filter_price_title') }}"

                route-get-adverts-list="{{ route('advert.index') }}"
                route-get-thumb="{{ route('picture.thumb', ['type' => \App\Common\PicturesManager::TYPE_FINAL_LOCAL])}}"
                ads-frenquency="{{ config('runtime.adsFrequency') }}"
                advert-title-label="{{ trans('strings.view_advert_form_title_label') }}"
                advert-description-label="{{ trans('strings.view_advert_form_description_label') }}"
                advert-price-label="{{ trans('strings.view_advert_form_price_label') }}"
                see-advert-link-label="{{ trans('strings.view_advert_by_link_label') }}"
                total-quantity-label="{{ trans('strings.form_quantity_label') }}"
                lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
                urgent-label="{{ trans('strings.view_all_urgent') }}"
                price-info-label="{{ trans('strings.view_advert_list_price_info') }}"

                page-label="{{ trans('strings.view_pagination_page_label') }}"
                page-previous-label="{{ trans('strings.view_pagination_prev_label') }}"
                page-next-label="{{ trans('strings.view_pagination_next_label') }}">
        </welcome2>
    @endif
@endsection
