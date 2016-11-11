@extends('layouts.app')

@section('content')

            <user-account
                    load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                    route-list-currencies="{{ route('utils.getListCurrencies') }}"
                    currencies-first-menu-name="{{ trans('strings.view_user_account_currency_dropdown_label') }}"
                    route-user-set-pref-currency="{{ route('user.currency') }}"
                    route-list-locales="{{ route('utils.getListLocales') }}"
                    locales-first-menu-name="{{ trans('strings.view_user_account_locale_dropdown_label') }}"
                    route-user-set-pref-locale="{{ route('user.locale') }}"
                    route-user-set-pref-location="{{ route('user.location') }}"
                    route-user-set-name="{{ route('user.name') }}"
                    route-user-set-compagny-name="{{ route('user.compagnyName') }}"
                    route-user-set-registration-number="{{ route('user.registrationNumber') }}"
                    account-patch-success="{{ trans('strings.view_user_account_patch_success') }}"
                    account-preferences-label="{{ trans('strings.view_user_account_preferences_label') }}"
                    route-avatar="{{ asset('/images/matt.jpg') }}"
                    input-search-label="{{ trans('strings.form_input_search_label') }}"
                    user-email="{{ $user->email }}"
                    user-name="{{ $user->name }}"
                    latitude="{{ $user->latitude }}"
                    longitude="{{ $user->longitude }}"
                    compagny-name="{{ $user->compagny_name }}"
                    registration-number="{{ $user->registration_number }}"
                    name-label="{{ trans('strings.form_label_name') }}"
                    email-label="{{ trans('strings.form_label_email') }}"
                    geoloc-help-msg="{{ trans('strings.form_googlemap_help') }}"
                    geoloc-help-msg-two="{{ trans('strings.form_googlemap_help2') }}"
                    googlemap-divider="{{ trans('strings.form_googlemap_label') }}"
                    compagny-divider="{{ trans('strings.view_user_account_compagny_divider_label') }}"
                    compagny-name-label="{{ trans('strings.view_user_account_compagny_name_label') }}"
                    compagny-number-label="{{ trans('strings.view_user_account_compagny_number_label') }}"
                    content-header="{{ trans('strings.view_advert_create_header') }}"
                    step-one-title="{{ trans('strings.view_advert_steps_1_title') }}"
                    step-two-title="{{ trans('strings.view_advert_steps_2_title') }}"
                    step-three-title="{{ trans('strings.view_advert_steps_3_title') }}"
                    step-one-description="{{ trans('strings.view_advert_steps_1_description') }}"
                    step-two-description="{{ trans('strings.view_advert_steps_2_description') }}"
                    step-three-description="{{ trans('strings.view_advert_steps_3_description') }}"
                    advert-account-verified-step="{{ isset($advertAccountVerifiedStep) ? $advertAccountVerifiedStep : false }}"
                    advert-cost="{{ isset($advert) ? $advert->cost : '0' }}"
                    advert-id="{{ isset($advert) ? $advert->id : '' }}"
                    form-compagny-name-min-valid="{{ config('db_limits.users.minCompagnyName') }}"
                    form-compagny-name-max-valid="{{ config('db_limits.users.maxCompagnyName') }}"
                    form-registration-number-min-valid="{{ config('db_limits.users.minRegistrationNumber') }}"
                    form-registration-number-max-valid="{{ config('db_limits.users.maxRegistrationNumber') }}"
                    form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimun_chars') }}"
                    form-validation-button-label="{{ trans('strings.form_button_validation') }}"
                    next-url-with-payment="{{ route('advert.publish') }}"
                    next-url-without-payment="{{ route('advert.publish') }}">
            </user-account>

@endsection

@section('scripts')
    @include('plugins.googleMap.script')
@endsection