@extends('layouts.app')

@section('content')

    <create-advert-form
            route-advert-form-post="{{ route('advert.store') }}"
            route-post-tempo-picture="{{ route('picture.post',['type'=>\App\Common\PicturesManager::TYPE_TEMPO_LOCAL]) }}"
            route-get-list-tempo-thumbs="{{ route('picture.listThumbs', ['type'=>\App\Common\PicturesManager::TYPE_TEMPO_LOCAL]) }}"
            route-get-tempo-thumb="{{ route('picture.thumb', ['type' => \App\Common\PicturesManager::TYPE_TEMPO_LOCAL])}}"
            route-del-tempo-picture="{{ route('picture.destroy', ['type' => \App\Common\PicturesManager::TYPE_TEMPO_LOCAL])}}"
            route-get-cost="{{ route('advert.cost')}}"

            @if(count(old())>0)old="{{ json_encode(old()) }}" @endif
            advert-form-photo-nb-free-picture="{{ env('NB_FREE_PICTURES') }}"
            max-files="{{ env('NB_MAX_PICTURES') }}"
            form-title-min-valid="{{ config('db_limits.adverts.minTitle') }}"
            form-title-max-valid="{{ config('db_limits.adverts.maxTitle') }}"
            form-description-min-valid="{{ config('db_limits.adverts.minDescription') }}"
            form-description-max-valid="{{ config('db_limits.adverts.maxDescription') }}"

            content-header="{{ trans('strings.view_advert_create_header') }}"
            advert-form-title-label="{{ trans('strings.view_advert_form_title_label') }}"
            advert-form-description-label="{{ trans('strings.view_advert_form_description_label') }}"
            advert-form-price-label="{{ trans('strings.view_advert_form_price_label') }}"
            advert-form-googlemap-label="{{ trans('strings.form_googlemap_label') }}"
            advert-form-photo-separator="{{ trans('strings.form_file_add_photo_separator') }}"
            advert-form-photo-label="{{ trans('strings.form_file_add_photo_label', ['minwidth' => env('MIN_WIDTH'), 'minheight' => env('MIN_HEIGHT'), 'maxsize' => env('FILE_MAX_SIZE')]) }}"
            advert-form-free-photo-help-header-singular="{{ trans_choice('strings.form_file_add_free_photo_help_header',1) }}"
            advert-form-free-photo-help-header-plural="{{ trans_choice('strings.form_file_add_free_photo_help_header',2) }}"
            advert-form-pay-photo-help-header-singular="{{ trans_choice('strings.form_file_add_pay_photo_help_header',1) }}"
            advert-form-pay-photo-help-header-plural="{{ trans_choice('strings.form_file_add_pay_photo_help_header',2) }}"
            advert-form-photo-help-content="{{ trans('strings.form_file_add_photo_help_content',['nb' => env('NB_FREE_PICTURES'), 'link' => route('home')]) }}"
            advert-form-main-photo-label="{{ trans('strings.form_radio_main_photo_label') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            filesize-error-message="{{ trans('strings.view_all_error_filesize_message') }}"
            form-validation-button-label="{{ trans('strings.form_button_validation') }}"
            form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimun_chars') }}"
            advert-form-total-quantity-label="{{ trans('strings.form_quantity_label') }}"
            advert-form-lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
            advert-form-urgent-label="{{ trans('strings.form_urgent', ['price' => env('URGENT_COST')]) }}"
            advert-example-urgent-label="{{ trans('strings.view_all_urgent') }}"

            step-one-title="{{ trans('strings.view_advert_steps_1_title') }}"
            step-two-title="{{ trans('strings.view_advert_steps_2_title') }}"
            step-three-title="{{ trans('strings.view_advert_steps_3_title') }}"
            step-one-description="{{ trans('strings.view_advert_steps_1_description') }}"
            step-two-description="{{ trans('strings.view_advert_steps_2_description') }}"
            step-three-description="{{ trans('strings.view_advert_steps_3_description') }}"

            route-get-list-type="{{ route('advert.getListType') }}"
            list-type-first-menu-name="{{ trans('strings.view_advert_list_type_dropdown_label') }}"

            route-category="{{ route('category.index') }}"
            category-first-menu-name="{{ trans('strings.menu_category') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"

            route-list-currencies="{{ route('utils.getListCurrencies') }}"
            currencies-first-menu-name="{{ trans('strings.view_user_account_currency_dropdown_label') }}"

            geoloc-help-msg="{{ trans('strings.form_googlemap_help') }}"
            geoloc-help-msg-two="{{ trans('strings.form_googlemap_help2') }}">
    </create-advert-form>

@endsection

@section('scripts')
    @include('plugins.googleMap.script')
@endsection