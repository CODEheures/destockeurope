@extends('layouts.app')

@section('content')

    <approve-advert-form
            route-get-adverts-list="{{ route('advert.listApprove') }}"
            route-advert-approve="{{ route('advert.approve') }}"
            route-get-thumb="{{ route('picture.thumb', ['type' => \App\Common\PicturesManager::TYPE_FINAL_LOCAL])}}"

            advert-nb-free-picture="{{ config('runtime.nbFreePictures') }}"

            content-header="{{ trans('strings.view_advert_approve_header') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            toggle-approve-label="{{ trans('strings.view_advert_approve_toggle_label') }}"
            toggle-disapprove-label="{{ trans('strings.view_advert_disapprove_toggle_label') }}"
            form-validation-button-label="{{ trans('strings.form_button_validation') }}"
            modal-valid-header="{{ trans('strings.view_advert_approve_modal_valid_header') }}"
            modal-valid-description="{{ trans('strings.view_advert_approve_modal_valid_description') }}"
            modal-no="{{ trans('strings.modal_no') }}"
            modal-yes="{{ trans('strings.modal_yes') }}"
            advert-pay-photo-singular="{{ trans_choice('strings.form_file_add_pay_photo_help_header',1) }}"
            advert-title-label="{{ trans('strings.view_advert_form_title_label') }}"
            advert-description-label="{{ trans('strings.view_advert_form_description_label') }}"
            advert-price-label="{{ trans('strings.view_advert_form_price_label') }}"
            advert-address-label="{{ trans('strings.view_advert_form_address_label') }}"
            advert-approve-success="{{ trans('strings.view_advert_approve_success') }}"
            total-quantity-label="{{ trans('strings.form_quantity_label') }}"
            lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}">
    </approve-advert-form>

@endsection