@extends('layouts.app')

@section('content')

    <user-account
            route-user-get-me="{{ route('user.getMe') }}"
            route-user-set-pref-currency="{{ route('user.currency') }}"
            route-user-set-pref-locale="{{ route('user.locale') }}"
            route-user-set-pref-location="{{ route('user.location') }}"
            route-user-set-name="{{ route('user.name') }}"
            route-user-set-phone="{{ route('user.phone') }}"
            route-user-set-compagny-name="{{ route('user.compagnyName') }}"
            route-user-set-registration-number="{{ route('user.registrationNumber') }}"
            route-avatar="{{ asset('/images/matt.jpg') }}"
            route-next-url-with-payment="{{ route('advert.reviewForPayment') }}"
            route-next-url-without-payment="{{ route('advert.publish') }}"

            user-email="{{ $user->email }}"
            user-name="{{ $user->name }}"
            user-phone="{{ $user->phone }}"
            latitude="{{ $user->latitude }}"
            longitude="{{ $user->longitude }}"
            first-geoloc="{{ $user->geoloc == null ? true : false }}"
            compagny-name="{{ $user->compagnyName }}"
            registration-number="{{ $user->registrationNumber }}"
            advert-account-verified-step="{{ isset($advertAccountVerifiedStep) ? $advertAccountVerifiedStep : false }}"
            advert-cost="{{ isset($advert) ? $advert->cost : '0' }}"
            advert-id="{{ isset($advert) ? $advert->id : '' }}"
            form-phone-max-valid="{{ config('db_limits.users.maxPhone') }}"
            form-compagny-name-min-valid="{{ config('db_limits.users.minCompagnyName') }}"
            form-compagny-name-max-valid="{{ config('db_limits.users.maxCompagnyName') }}"
            form-registration-number-min-valid="{{ config('db_limits.users.minRegistrationNumber') }}"
            form-registration-number-max-valid="{{ config('db_limits.users.maxRegistrationNumber') }}"

            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            account-patch-success="{{ trans('strings.view_user_account_patch_success') }}"
            account-preferences-label="{{ trans('strings.view_user_account_preferences_label') }}"
            name-label="{{ trans('strings.form_label_name') }}"
            email-label="{{ trans('strings.form_label_email') }}"
            phone-label="{{ trans('strings.form_label_phone') }}"
            compagny-divider="{{ trans('strings.view_user_account_compagny_divider_label') }}"
            compagny-name-label="{{ trans('strings.view_user_account_compagny_name_label') }}"
            compagny-number-label="{{ trans('strings.view_user_account_compagny_number_label') }}"
            content-header="{{ isset($title) ?  $title : trans('strings.view_advert_create_header') }}"
            geoloc-help-msg="{{ trans('strings.form_googlemap_help') }}"
            geoloc-help-msg-two="{{ trans('strings.form_googlemap_help2') }}"
            googlemap-divider="{{ trans('strings.form_googlemap_label') }}"
            form-validation-button-label="{{ trans('strings.form_button_validation') }}"
            form-validation-fails-button-label="{{ trans('strings.view_all_error_reload_message') }}"
            form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimum_chars') }}"

            step-one-title="{{ trans('strings.view_advert_steps_1_title') }}"
            step-two-title="{{ trans('strings.view_advert_steps_2_title') }}"
            step-three-title="{{ trans('strings.view_advert_steps_3_title') }}"
            step-one-description="{{ trans('strings.view_advert_steps_1_description') }}"
            step-two-description="{{ trans('strings.view_advert_steps_2_description') }}"
            step-three-description="{{ trans('strings.view_advert_steps_3_description') }}"

            route-list-locales="{{ route('utils.getListLocales') }}"
            locales-first-menu-name="{{ trans('strings.view_user_account_locale_dropdown_label') }}"
            input-search-label="{{ trans('strings.form_input_search_label') }}"

            route-list-currencies="{{ route('utils.getListCurrencies') }}"
            currencies-first-menu-name="{{ trans('strings.view_user_account_currency_dropdown_label') }}">
    </user-account>

@endsection

@section('scripts')
    @include('plugins.googleMap.map.script')
@endsection