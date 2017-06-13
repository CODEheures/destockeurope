@extends('layouts.app')

@section('content')

    <edit-advert-form
            route-advert-form-post="{{ route('advert.update', ['id' => $editAdvert->id]) }}"
            route-post-tempo-picture="{{ route('picture.post',['type'=>\App\Common\PicturesManager::TYPE_TEMPO_LOCAL]) }}"
            route-get-list-tempo-thumbs="{{ route('picture.listThumbs', ['type'=>\App\Common\PicturesManager::TYPE_TEMPO_LOCAL]) }}"
            route-get-tempo-thumb="{{ route('picture.thumb', ['type' => \App\Common\PicturesManager::TYPE_TEMPO_LOCAL])}}"
            route-del-tempo-picture="{{ route('picture.destroyTempo')}}"
            route-get-cost="{{ route('advert.cost')}}"
            route-prices="{{ route('prices') }}"

            old="{{ count(old())>0 ? 1 : 0 }}"
            advert-form-photo-nb-free-picture="{{ config('runtime.nbFreePictures') }}"
            max-files="{{ config('runtime.nbMaxPictures') }}"
            form-title-min-valid="{{ config('db_limits.adverts.minTitle') }}"
            form-title-max-valid="{{ config('db_limits.adverts.maxTitle') }}"
            form-description-min-valid="{{ config('db_limits.adverts.minDescription') }}"
            form-description-max-valid="{{ config('db_limits.adverts.maxDescription') }}"
            is-delegation="{{ isset($user) ? $user->isSupplier : false }}"
            edit-advert="{{ isset($editAdvert) ? json_encode($editAdvert) : '' }}"

            content-header="{{ isset($user) && $user->isSupplier ? trans('strings.view_advert_create_header_delegation'):trans('strings.view_advert_create_header') }}"
            advert-form-title-label="{{ trans('strings.view_advert_form_title_label') }}"
            advert-form-ref-label="{{ trans('strings.view_advert_form_ref_label') }}"
            advert-form-description-label="{{ trans('strings.view_advert_form_description_label') }}"
            advert-form-price-label="{{ trans('strings.view_advert_form_price_label') }}"
            advert-form-googlemap-label="{{ trans('strings.form_googlemap_label') }}"
            advert-form-photo-separator="{{ trans('strings.form_file_add_photo_separator') }}"
            advert-form-photo-btn-label="{{ trans('strings.form_file_add_photo_btn_label') }}"
            advert-form-photo-label="{{ trans('strings.form_file_add_photo_label', ['minwidth' => env('MIN_WIDTH'), 'minheight' => env('MIN_HEIGHT'), 'maxsize' => env('PHOTO_MAX_SIZE_MB')]) }}"
            advert-form-free-photo-help-header-singular="{{ trans_choice('strings.form_file_add_free_photo_help_header',1) }}"
            advert-form-free-photo-help-header-plural="{{ trans_choice('strings.form_file_add_free_photo_help_header',2) }}"
            advert-form-pay-photo-help-header-singular="{{ trans_choice('strings.form_file_add_pay_photo_help_header',1) }}"
            advert-form-pay-photo-help-header-plural="{{ trans_choice('strings.form_file_add_pay_photo_help_header',2) }}"
            advert-form-photo-help-content="{{ trans('strings.form_file_add_photo_help_content',['nb' => config('runtime.nbFreePictures'), 'link' => route('home')]) }}"
            advert-form-main-photo-label="{{ trans('strings.form_radio_main_photo_label') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            filesize-error-message="{{ trans('strings.view_all_error_filesize_message') }}"
            form-validation-button-label="{{ trans('strings.form_button_validation') }}"
            form-pointing-minimum-chars="{{ trans('strings.form_pointing_minimum_chars') }}"
            advert-form-total-quantity-label="{{ trans('strings.form_quantity_label') }}"
            advert-form-lot-mini-quantity-label="{{ trans('strings.form_lot_mini_label') }}"
            advert-form-urgent-label="{{ isset($user) && $user->isSupplier ? trans('strings.form_urgent_delegation', ['price' => config('runtime.urgentCost')]):trans('strings.form_urgent', ['price' => config('runtime.urgentCost')]) }}"
            advert-example-urgent-label="{{ trans('strings.view_all_urgent') }}"
            advert-form-is-negociated-label="{{ trans('strings.form_isNegociated') }}"
            advert-example-is-negociated-label="{{ trans('strings.view_all_negociate') }}"
            advert-price="{{ trans('strings.view_price_header') }}"

            step-one-title="{{ trans('strings.view_advert_steps_1_title') }}"
            step-two-title="{{ trans('strings.view_advert_steps_2_title') }}"
            step-three-title="{{ trans('strings.view_advert_steps_3_title') }}"
            step-three-title-post="{{ trans('strings.view_advert_steps_3_title_post') }}"
            step-one-description="{{ trans('strings.view_advert_steps_1_description') }}"
            step-two-description="{{ isset($user) && $user->isSupplier ? trans('strings.view_advert_steps_2_description_delegation'):trans('strings.view_advert_steps_2_description') }}"
            step-three-description="{{ isset($user) && $user->isSupplier ? trans('strings.view_advert_steps_3_description_delegation'):trans('strings.view_advert_steps_3_description') }}"

            route-get-list-type="{{ route('advert.getListType') }}"
            list-type-first-menu-name="{{ trans('strings.view_advert_list_type_dropdown_label') }}"

            route-category="{{ route('category.index') }}"
            category-first-menu-name="{{ trans('strings.menu_category') }}"
            actual-locale="{{ \Illuminate\Support\Facades\App::getLocale() }}"

            route-list-currencies="{{ route('utils.getListCurrencies') }}"
            currencies-first-menu-name="{{ trans('strings.view_user_account_currency_dropdown_label') }}"

            geoloc-help-msg="{{ trans('strings.form_googlemap_help') }}"
            geoloc-help-msg-two="{{ trans('strings.form_googlemap_help2') }}"

            route-get-video-post-ticket="{{ route('videos.ticket') }}"
            route-del-tempo-video="{{ route('videos.delTempo') }}"
            route-get-status-video="{{ route('videos.status') }}"
            max-video-file-size="{{ env('VIDEO_MAX_SIZE_MB')*1024*1024 }}"
            session-video-id="{{ session()->has('videoId') ? session('videoId') : null }}"
            advert-form-video-separator="{{ trans('strings.form_file_add_video_separator') }}"
            advert-form-video-label="{{ trans('strings.form_file_add_video_label', ['maxsize' => env('VIDEO_MAX_SIZE_MB')]) }}"
            advert-form-video-btn-delete="{{ trans('strings.form_file_del_video_label') }}"
            advert-form-video-btn-cancel="{{ trans('strings.form_file_cancel_video_label') }}"
            waiting-message="{{ trans('strings.form_waiting_for_process') }}"
            transcode-message="{{ trans('strings.form_waiting_for_transcode') }}"

    ></edit-advert-form>

@endsection

@section('scripts')
    @include('plugins.googleMap.map.script')
@endsection