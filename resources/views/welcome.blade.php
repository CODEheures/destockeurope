@extends('layouts.app')

@section('content')
    <welcome1
                clear-storage="{{ session()->has('clear') ? true : false }}"

                load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                bookmark-success="{{ trans('strings.view_advert_show_bookmark_success') }}"
                unbookmark-success="{{ trans('strings.view_advert_show_unbookmark_success') }}"
                modal-valid-header="{{ trans('strings.view_advert_show_modal_delete_header') }}"
                modal-valid-description="{{ trans('strings.view_advert_show_modal_delete_description') }}"
                modal-no="{{ trans('strings.modal_no') }}"
                modal-yes="{{ trans('strings.modal_yes') }}"
                all-label="{{ trans('strings.view_all_all') }}"

                route-category="{{ route('category.index') }}"
                categories-dropdown-menu-first-menu-name="{{ trans('strings.menu_category') }}"
                categories-all-item="{{ trans('strings.form_dropdown_all_item') }}"
                actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"

                masterads-route-image-server = "{{ route('imageServer') }}"
                masterads-is-active="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && isset($masterAdsControllerFlag) ? $masterAdsControllerFlag : false }}"
                masterads-url-img="{{config('runtime.urlMasterAds')}}"
                masterads-url-redirect="{{ config('runtime.urlLinkMasterAds') }}"
                masterads-offset-y-main-container="{{ config('runtime.offsetYMasterAds') }}"
                masterads-width="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && isset($masterAdsControllerFlag) ? config('runtime.widthUrlMasterAds') : 0 }}"

                filter-min-length-search="{{ config('runtime.minLengthSearch') }}"
                filter-location-accurate-list="{{ json_encode(\App\Common\GeoManager::$accurate) }}"
                filter-ribbon-open="{{ trans('strings.view_filter_ribbon_open') }}"
                filter-ribbon-close="{{ trans('strings.view_filter_ribbon_close') }}"
                filter-urgent-label="{{ trans('strings.view_all_urgent') }}"
                filter-search-place-holder="{{ trans('strings.form_input_search_label2') }}"
                filter-location-place-holder="{{ trans('strings.form_input_location_label') }}"
                filter-price-title="{{ trans('strings.view_filter_price_title') }}"
                filter-quantity-title="{{ trans('strings.form_quantity_label') }}"
                filter-currencies-first-menu-name="{{ trans('strings.view_user_account_currency_dropdown_label') }}"
                filter-currencies-input-search-label="{{ trans('strings.form_input_search_label') }}"
                filter-currencies-with-all-label="{{ trans('strings.form_dropdown_all_item') }}"

                route-get-adverts-list="{{ route('advert.index') }}"
                route-bookmark-add="{{ route('bookmark.add') }}"
                route-bookmark-remove="{{ route('bookmark.remove') }}"
                ads-frenquency="{{ config('runtime.adsFrequency') }}"
                total-quantity-label="{{ trans('strings.form_quantity_label') }}"
                lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
                urgent-label="{{ trans('strings.view_all_urgent') }}"
                is-negociated-label="{{ trans('strings.view_all_negociate') }}"
                price-info-label="{{ trans('strings.view_advert_list_price_info') }}"
                no-result-found-header="{{ trans('strings.view_advert_list_no_result_header') }}"
                no-result-found-message="{{ trans('strings.view_advert_list_no_result_message') }}"
                route-notifications-exist-in="{{ route('notification.existIn') }}"
                route-notifications-add="{{ route('notification.add') }}"
                route-notifications-remove="{{ route('notification.remove') }}"
                notifications-checkbox-label="{{ trans('strings.form_notification_activer_label') }}"

                page-label="{{ trans('strings.view_pagination_page_label') }}"
                page-previous-label="{{ trans('strings.view_pagination_prev_label') }}"
                page-next-label="{{ trans('strings.view_pagination_next_label') }}"

                route-get-highlight="{{ route('getHighlight') }}"
                fake-highlight-advert="{{ json_encode($fakeHighlightAdvert) }}"
        ></welcome1>
@endsection

@section('scripts')
    @include('plugins.googleMap.autocomplete.script')
@endsection
