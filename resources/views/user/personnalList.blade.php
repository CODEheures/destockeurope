@extends('layouts.app')

@section('content')
        <personnal-list
                load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                content-header="{{ trans('strings.menu_mines') }}"
                modal-valid-header="{{ trans('strings.view_advert_show_modal_delete_header') }}"
                modal-valid-description="{{ trans('strings.view_advert_show_modal_delete_description') }}"
                modal-no="{{ trans('strings.modal_no') }}"
                modal-yes="{{ trans('strings.modal_yes') }}"

                route-get-adverts-list="{{ route('advert.mines') }}"

                actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
                ads-frequency="{{ 0 }}"
                advert-title-label="{{ trans('strings.view_advert_form_title_label') }}"
                advert-description-label="{{ trans('strings.view_advert_form_description_label') }}"
                advert-price-label="{{ trans('strings.view_advert_form_price_label') }}"
                see-advert-link-label="{{ trans('strings.view_advert_by_link_label') }}"
                total-quantity-label="{{ trans('strings.form_quantity_label') }}"
                lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
                urgent-label="{{ trans('strings.view_all_urgent') }}"
                price-info-label="{{ trans('strings.view_advert_list_price_info') }}"
                manage-advert-label="{{ trans('strings.view_advert_show_manage_label') }}"
                renew-advert-label="{{ trans('strings.view_advert_show_renew_label') }}"
                is-renew-advert-label="{{ trans('strings.view_advert_show_is_renew_label') }}"
                bookmark-info="{{ trans('strings.view_advert_show_bookmark_info') }}"
                views-info="{{ trans('strings.view_advert_show_views_info') }}"
                no-result-found-header="{{ trans('strings.view_advert_list_no_result_header') }}"
                no-result-found-message="{{ trans('strings.view_advert_list_no_result_message') }}"

                page-label="{{ trans('strings.view_pagination_page_label') }}"
                page-previous-label="{{ trans('strings.view_pagination_prev_label') }}"
                page-next-label="{{ trans('strings.view_pagination_next_label') }}">
        </personnal-list>
@endsection
