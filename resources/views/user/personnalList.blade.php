@extends('layouts.app')

@section('content')
        <personnal-list
                load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                update-success-message="{{ trans('strings.view_user_account_patch_success') }}"
                content-header="{{ isset($title) ?  $title : trans('strings.menu_mines') }}"
                modal-valid-header="{{ trans('strings.view_advert_show_modal_delete_header') }}"
                modal-valid-description="{{ trans('strings.view_advert_show_modal_delete_description') }}"
                modal-no="{{ trans('strings.modal_no') }}"
                modal-yes="{{ trans('strings.modal_yes') }}"

                route-get-adverts-list="{{ $routeList }}"
                route-update-price-coefficient="{{ auth()->user()->role=='admin' ? route('advert.updateCoefficient'):'' }}"
                actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
                ads-frequency="{{ 0 }}"
                is-admin-user="{{ auth()->user()->role=='admin' }}"
                is-personnal-list="{{ true }}"
                advert-title-label="{{ trans('strings.view_advert_form_title_label') }}"
                advert-description-label="{{ trans('strings.view_advert_form_description_label') }}"
                advert-price-label="{{ trans('strings.view_advert_form_price_label') }}"
                see-advert-link-label="{{ trans('strings.view_advert_by_link_label') }}"
                total-quantity-label="{{ trans('strings.form_quantity_label') }}"
                lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
                urgent-label="{{ trans('strings.view_all_urgent') }}"
                is-negociated-label="{{ trans('strings.view_all_negociate') }}"
                price-info-label="{{ trans('strings.view_advert_list_price_info') }}"
                manage-advert-label="{{ trans('strings.view_advert_show_manage_label') }}"
                renew-advert-label="{{ trans('strings.view_advert_show_renew_label') }}"
                delete-advert-label="{{ trans('strings.view_advert_show_delete2_label') }}"
                see-advert-label="{{ trans('strings.view_advert_show_see_label') }}"
                validation-on-progress-label="{{ trans('strings.view_advert_validation_on_progress') }}"
                is-renew-advert-label="{{ trans('strings.view_advert_show_is_renew_label') }}"
                bookmark-info="{{ trans('strings.view_advert_show_bookmark_info') }}"
                views-info="{{ trans('strings.view_advert_show_views_info') }}"
                no-result-found-header="{{ trans('strings.view_advert_list_no_result_header') }}"
                no-result-found-message="{{ trans('strings.view_advert_list_no_result_message') }}"
                form-advert-price-coefficient-label="{{ trans('strings.view_advert_priceCoefficient_label') }}"
                form-advert-price-coefficient-new-price-label="{{ trans('strings.view_advert_priceCoefficient_new_price') }}"
                form-advert-price-coefficient-unit-margin-label="{{ trans('strings.view_advert_priceCoefficient_unit_margin') }}"
                form-advert-price-coefficient-total-margin-label="{{ trans('strings.view_advert_priceCoefficient_total_margin') }}"
                form-advert-price-coefficient-update-label="{{ trans('strings.view_advert_show_update_label') }}"

                page-label="{{ trans('strings.view_pagination_page_label') }}"
                page-previous-label="{{ trans('strings.view_pagination_prev_label') }}"
                page-next-label="{{ trans('strings.view_pagination_next_label') }}">
        </personnal-list>
@endsection
