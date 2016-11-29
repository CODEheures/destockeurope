@extends('layouts.app')

@section('content')

    <show-advert1
            route-send-mail="{{ route('advert.sendMail') }}"

            user-mail="{{ auth()->check() ? auth()->user()->email : '' }}"
            user-name="{{ auth()->check() ? auth()->user()->name : '' }}"
            is-user-owner="{{ auth()->check() ? $advert->user->id == auth()->user()->id : false }}"
            is-user-bookmark="{{ auth()->check() ? auth()->user()->haveBookmark($advert->id) : false }}"
            bookmark-count="{{ $advert->bookmarks->count() }}"
            form-name-min-valid="{{ config('db_limits.users.minName') }}"
            form-message-min-valid="{{ config('db_limits.messages.minLength') }}"
            form-message-max-valid="{{ config('db_limits.messages.maxLength') }}"

            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            send-success-message="{{ trans('strings.view_advert_show_message_send') }}"
            form-validation-email="{{ trans('strings.form_validation_email') }}"
            form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimum_chars') }}"
            form-pointing-maximum-chars="{{ trans('strings.form_pointing_maximum_chars') }}"
            contact-label="{{ trans('strings.view_advert_show_contact_label') }}"
            bookmark-info="{{ trans('strings.view_advert_show_bookmark_info') }}"
            bookmark-label="{{ trans('strings.view_advert_show_bookmark_label') }}"
            unbookmark-label="{{ trans('strings.view_advert_show_unbookmark_label') }}"
            delete-label="{{ trans('strings.view_advert_show_delete_label') }}"
            form-message-label="{{ trans('strings.form_label_message_input') }}"
            form-message-email-label="{{ trans('strings.form_label_email') }}"
            form-message-name-label="{{ trans('strings.form_label_name') }}"
            form-message-send-label="{{ trans('strings.modal_send') }}"
            form-message-cancel-label="{{ trans('strings.modal_cancel') }}"

            route-get-advert="{{ route('advert.show', ['id' => $advert->id]) }}"
            route-home="{{ route('home') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
            image-ratio="{{ env('IMAGE_RATIO') }}"
            total-quantity-label="{{ trans('strings.form_quantity_label') }}"
            lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
            urgent-label="{{ trans('strings.view_all_urgent') }}"
            price-info-label="{{ trans('strings.view_advert_list_price_info') }}"
            price-label="{{ trans('strings.view_advert_form_price_label') }}">
    </show-advert1>

@endsection