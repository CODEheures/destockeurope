@extends('layouts.app')

@section('content')
    @if(config('runtime.welcomeType')=='2')
        <welcome1
                clear-storage="{{ session()->has('clear') ? true : false }}"

                load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                bookmark-success="{{ trans('strings.view_advert_show_bookmark_success') }}"
                unbookmark-success="{{ trans('strings.view_advert_show_unbookmark_success') }}"
                modal-valid-header="{{ trans('strings.view_advert_show_modal_delete_header') }}"
                modal-valid-description="{{ trans('strings.view_advert_show_modal_delete_description') }}"
                modal-no="{{ trans('strings.modal_no') }}"
                modal-yes="{{ trans('strings.modal_yes') }}"

                route-category="{{ route('category.index') }}"
                categories-dropdown-menu-first-menu-name="{{ trans('strings.menu_category') }}"
                categories-all-item="{{ trans('strings.form_dropdown_all_item') }}"
                actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"

                filter-min-length-search="{{ config('runtime.minLengthSearch') }}"
                filter-location-accurate-list="{{ json_encode(\App\Common\GeoManager::$accurate) }}"
                filter-ribbon="{{ trans('strings.view_filter_ribbon') }}"
                filter-urgent-label="{{ trans('strings.view_all_urgent') }}"
                filter-search-place-holder="{{ trans('strings.form_input_search_label2') }}"
                filter-location-place-holder="{{ trans('strings.form_input_location_label') }}"
                filter-price-title="{{ trans('strings.view_filter_price_title') }}"
                filter-quantity-title="{{ trans('strings.form_quantity_label') }}"

                route-get-adverts-list="{{ route('advert.index') }}"
                route-bookmark-add="{{ route('bookmark.add') }}"
                route-bookmark-remove="{{ route('bookmark.remove') }}"
                ads-frenquency="{{ config('runtime.adsFrequency') }}"
                advert-title-label="{{ trans('strings.view_advert_form_title_label') }}"
                advert-description-label="{{ trans('strings.view_advert_form_description_label') }}"
                advert-price-label="{{ trans('strings.view_advert_form_price_label') }}"
                see-advert-link-label="{{ trans('strings.view_advert_by_link_label') }}"
                total-quantity-label="{{ trans('strings.form_quantity_label') }}"
                lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
                urgent-label="{{ trans('strings.view_all_urgent') }}"
                price-info-label="{{ trans('strings.view_advert_list_price_info') }}"
                no-result-found-header="{{ trans('strings.view_advert_list_no_result_header') }}"
                no-result-found-message="{{ trans('strings.view_advert_list_no_result_message') }}"

                page-label="{{ trans('strings.view_pagination_page_label') }}"
                page-previous-label="{{ trans('strings.view_pagination_prev_label') }}"
                page-next-label="{{ trans('strings.view_pagination_next_label') }}">
        </welcome1>
    @elseif(config('runtime.welcomeType')=='1')
        <welcome2
                clear-storage="{{ session()->has('clear') ? true : false }}"

                load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                bookmark-success="{{ trans('strings.view_advert_show_bookmark_success') }}"
                unbookmark-success="{{ trans('strings.view_advert_show_unbookmark_success') }}"
                modal-valid-header="{{ trans('strings.view_advert_show_modal_delete_header') }}"
                modal-valid-description="{{ trans('strings.view_advert_show_modal_delete_description') }}"
                modal-no="{{ trans('strings.modal_no') }}"
                modal-yes="{{ trans('strings.modal_yes') }}"

                route-category="{{ route('category.index') }}"
                categories-dropdown-menu-first-menu-name="{{ trans('strings.menu_category') }}"
                categories-all-item="{{ trans('strings.form_dropdown_all_item') }}"
                actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"

                filter-min-length-search="{{ config('runtime.minLengthSearch') }}"
                filter-location-accurate-list="{{ json_encode(\App\Common\GeoManager::$accurate) }}"
                filter-ribbon="{{ trans('strings.view_filter_ribbon') }}"
                filter-urgent-label="{{ trans('strings.view_all_urgent') }}"
                filter-search-place-holder="{{ trans('strings.form_input_search_label2') }}"
                filter-location-place-holder="{{ trans('strings.form_input_location_label') }}"
                filter-price-title="{{ trans('strings.view_filter_price_title') }}"
                filter-quantity-title="{{ trans('strings.form_quantity_label') }}"

                route-get-adverts-list="{{ route('advert.index') }}"
                route-bookmark-add="{{ route('bookmark.add') }}"
                route-bookmark-remove="{{ route('bookmark.remove') }}"
                ads-frenquency="{{ config('runtime.adsFrequency') }}"
                advert-title-label="{{ trans('strings.view_advert_form_title_label') }}"
                advert-description-label="{{ trans('strings.view_advert_form_description_label') }}"
                advert-price-label="{{ trans('strings.view_advert_form_price_label') }}"
                see-advert-link-label="{{ trans('strings.view_advert_by_link_label') }}"
                total-quantity-label="{{ trans('strings.form_quantity_label') }}"
                lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
                urgent-label="{{ trans('strings.view_all_urgent') }}"
                price-info-label="{{ trans('strings.view_advert_list_price_info') }}"
                no-result-found-header="{{ trans('strings.view_advert_list_no_result_header') }}"
                no-result-found-message="{{ trans('strings.view_advert_list_no_result_message') }}"

                page-label="{{ trans('strings.view_pagination_page_label') }}"
                page-previous-label="{{ trans('strings.view_pagination_prev_label') }}"
                page-next-label="{{ trans('strings.view_pagination_next_label') }}">
        </welcome2>
    @endif
@endsection

@section('scripts')
    @include('plugins.googleMap.autocomplete.script')
@endsection
