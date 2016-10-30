@extends('layouts.app')

@section('content')

    <create-advert-form
            content-header="{{ trans('strings.view_advert_create_header') }}"
            advert-form-title-label="{{ trans('strings.view_advert_form_title_label') }}"
            advert-form-description-label="{{ trans('strings.view_advert_form_description_label') }}"
            advert-form-price-label="{{ trans('strings.view_advert_form_price_label') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            route-category="{{ route('category.index') }}"
            category-first-menu-name="{{ trans('strings.menu_category') }}"
            route-get-list-type="{{ route('advert.getListType') }}"
            list-type-first-menu-name="{{ trans('strings.view_advert_list_type_dropdown_label') }}"
            form-validation-button-label="{{ trans('strings.form_button_validation') }}"
            route-advert-form-post="{{ route('advert.store') }}"
            form-title-min-valid="{{ config('db_limits.adverts.minTitle') }}"
            form-title-max-valid="{{ config('db_limits.adverts.maxTitle') }}"
            form-description-min-valid="{{ config('db_limits.adverts.minDescription') }}"
            form-description-max-valid="{{ config('db_limits.adverts.maxDescription') }}"
            form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimun_chars') }}"
            route-list-currencies="{{ route('utils.getListCurrencies') }}"
            currencies-first-menu-name="{{ trans('strings.view_user_account_currency_dropdown_label') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
            @if(count(old())>0)
                old="{{ json_encode(old()) }}"
            @endif>
    </create-advert-form>

@endsection