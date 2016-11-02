@extends('layouts.app')

@section('content')

    <create-advert-form
            content-header="{{ trans('strings.view_advert_create_header') }}"
            advert-form-title-label="{{ trans('strings.view_advert_form_title_label') }}"
            advert-form-description-label="{{ trans('strings.view_advert_form_description_label') }}"
            advert-form-price-label="{{ trans('strings.view_advert_form_price_label') }}"
            advert-form-googlemap-label="{{ trans('strings.form_googlemap_label') }}"
            advert-form-photo-separator="{{ trans('strings.form_file_add_photo_separator') }}"
            advert-form-photo-label="{{ trans('strings.form_file_add_photo_label', ['maxsize' => env('FILE_MAX_SIZE')]) }}"
            advert-form-free-photo-help-header-singular="{{ trans_choice('strings.form_file_add_free_photo_help_header',1) }}"
            advert-form-free-photo-help-header-plural="{{ trans_choice('strings.form_file_add_free_photo_help_header',2) }}"
            advert-form-pay-photo-help-header-singular="{{ trans_choice('strings.form_file_add_pay_photo_help_header',1) }}"
            advert-form-pay-photo-help-header-plural="{{ trans_choice('strings.form_file_add_pay_photo_help_header',2) }}"
            advert-form-photo-help-content="{{ trans('strings.form_file_add_photo_help_content',['nb' => env('NB_FREE_PICTURES'), 'link' => route('home')]) }}"
            advert-form-photo-nb-free-picture="{{ env('NB_FREE_PICTURES') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            filesize-error-message="{{ trans('strings.view_all_error_filesize_message') }}"
            max-files="{{ env('NB_MAX_PICTURES') }}"
            route-category="{{ route('category.index') }}"
            category-first-menu-name="{{ trans('strings.menu_category') }}"
            route-get-list-type="{{ route('advert.getListType') }}"
            list-type-first-menu-name="{{ trans('strings.view_advert_list_type_dropdown_label') }}"
            form-validation-button-label="{{ trans('strings.form_button_validation') }}"
            route-advert-form-post="{{ route('advert.store') }}"
            route-advert-pictures-post="{{ route('advert.tempoPictures') }}"
            form-title-min-valid="{{ config('db_limits.adverts.minTitle') }}"
            form-title-max-valid="{{ config('db_limits.adverts.maxTitle') }}"
            form-description-min-valid="{{ config('db_limits.adverts.minDescription') }}"
            form-description-max-valid="{{ config('db_limits.adverts.maxDescription') }}"
            form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimun_chars') }}"
            route-list-currencies="{{ route('utils.getListCurrencies') }}"
            currencies-first-menu-name="{{ trans('strings.view_user_account_currency_dropdown_label') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"
            geoloc-help-msg="{{ trans('strings.form_googlemap_help') }}"
            @if(count(old())>0)
                old="{{ json_encode(old()) }}"
            @endif>
    </create-advert-form>

@endsection

@section('scripts')
    @include('plugins.googleMap.script')
@endsection