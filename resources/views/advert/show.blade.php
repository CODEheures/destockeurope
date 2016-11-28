@extends('layouts.app')

@section('content')

    <show-advert1
            route-send-mail="{{ route('advert.sendMail') }}"

            user-mail="{{ auth()->check() ? auth()->user()->email : '' }}"
            form-message-min-valid="{{ config('db_limits.messages.minLength') }}"
            form-message-max-valid="{{ config('db_limits.messages.maxLength') }}"

            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            send-success-message="{{ trans('strings.view_advert_show_message_send') }}"
            form-validation-email="{{ trans('strings.form_validation_email') }}"
            form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimum_chars') }}"
            form-pointing-maximum-chars="{{ trans('strings.form_pointing_maximum_chars') }}"

            route-get-advert="{{ route('advert.show', ['id' => $advert->id]) }}"
            route-home="{{ route('home') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
            image-ratio="{{ env('IMAGE_RATIO') }}"
            total-quantity-label="{{ trans('strings.form_quantity_label') }}"
            lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
            urgent-label="{{ trans('strings.view_all_urgent') }}"
            price-info-label="{{ trans('strings.view_advert_list_price_info') }}"
            price-label="{{ trans('strings.view_advert_form_price_label') }}"
            contact-label="{{ trans('strings.view_advert_show_contact_label') }}"
            form-message-label="{{ trans('strings.form_label_message_input') }}"
            form-message-email-label="{{ trans('strings.form_label_email') }}"
            form-message-send-label="{{ trans('strings.modal_send') }}"
            form-message-cancel-label="{{ trans('strings.modal_cancel') }}">
    </show-advert1>

@endsection