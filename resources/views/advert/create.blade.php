@extends('layouts.app')

@section('content')
    <div class="ui one column grid">
        <div class="column">
            <h2 class="ui header">{{ trans('strings.view_advert_create_header') }}</h2>
            <div class="one wide column">
                <create-advert-form
                        advert-form-title-label="{{ trans('strings.view_advert_form_title_label') }}"
                        advert-form-description-label="{{ trans('strings.view_advert_form_description_label') }}"
                        advert-form-price-label="{{ trans('strings.view_advert_form_price_label') }}"
                        category-load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                        category-route-meta-category="{{ route('metaCategory.index') }}"
                        category-first-menu-name="{{ trans('strings.menu_category') }}"
                        route-get-list-type="{{ route('advert.getListType') }}"
                        advert-list-type-load-error-message="{{ trans('strings.view_all_error_load_message') }}"
                        list-type-first-menu-name="{{ trans('strings.view_advert_list_type_dropdown_label') }}"
                        form-validation-button-label="{{ trans('strings.form_button_validation') }}"
                        route-advert-form-post="{{ route('advert.store') }}"
                        form-title-min-valid="{{ config('db_limits.adverts.minTitle') }}"
                        form-title-max-valid="{{ config('db_limits.adverts.maxTitle') }}"
                        form-description-min-valid="{{ config('db_limits.adverts.minDescription') }}"
                        form-description-max-valid="{{ config('db_limits.adverts.maxDescription') }}"
                        form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimun_chars') }}"
                        @if(count(old())>0)
                            old="{{ json_encode(old()) }}"
                        @endif>
                </create-advert-form>
            </div>
        </div>
    </div>
@endsection