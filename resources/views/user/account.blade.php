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
                    account-patch-success="{{ trans('strings.view_user_account_patch_success') }}"
                    account-preferences-label="{{ trans('strings.view_user_account_preferences_label') }}"
                    route-avatar="{{ asset('/images/matt.jpg') }}"
                    input-search-label="{{ trans('strings.form_input_search_label') }}"
                    user-name="{{ $user->name }}"
                    geoloc-help-msg="{{ trans('strings.form_googlemap_help') }}"
                    geoloc-help-msg-two="{{ trans('strings.form_googlemap_help2') }}"
                    account-googlemap-label="{{ trans('strings.form_googlemap_label') }}">
            </user-account>

@endsection

@section('scripts')
    @include('plugins.googleMap.script')
@endsection