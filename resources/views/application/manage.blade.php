@extends('layouts.app')

@section('content')

    <manage-application
            route-parameters="{{ route('application.parameters') }}"
            route-test-is-picture="{{ route('utils.isPicture') }}"

            content-header="{{ trans('strings.view_manage_index_header') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            patch-error-message="{{ trans('strings.view_all_error_patch_message') }}"
            patch-success-message="{{ trans('strings.view_all_success_patch_message') }}"
            invalid-image-message="{{ trans('strings.view_all_error_invalid_image_message') }}"
            advert-preferences-label="{{ trans('strings.view_manage_adverts_label') }}"
            advert-nb-free-pictures-label="{{ trans('strings.view_manage_adverts_nb_free_pictures_label') }}"
            advert-nb-max-pictures-label="{{ trans('strings.view_manage_adverts_nb_max_pictures_label') }}"
            advert-urgent-cost-label="{{ trans('strings.view_manage_adverts_urgent_cost_label') }}"
            advert-video-cost-label="{{ trans('strings.view_manage_adverts_video_cost_label') }}"
            advert-renew-cost-label="{{ trans('strings.view_manage_adverts_renew_cost_label') }}"
            advert-per-page-label="{{ trans('strings.view_manage_adverts_per_page_label') }}"
            advert-resume-lenght-label="{{ trans('strings.view_manage_resume_length_label') }}"
            search-label="{{ trans('strings.view_manage_search_label') }}"
            min-length-search-label="{{ trans('strings.view_manage_min_length_search_label') }}"
            max-number-of-search-results-label="{{ trans('strings.view_manage_max_result_search_label') }}"
            ads-preferences-label="{{ trans('strings.view_manage_ads_label') }}"
            ads-frequency-label="{{ trans('strings.view_manage_ads_frequency_label') }}"
            master-ads-activation-label="{{ trans('strings.view_manage_master_ads_activation_label') }}"
            master-ads-url-label="{{ trans('strings.view_manage_master_ads_url_label') }}"
            master-ads-url-link-label="{{ trans('strings.view_manage_master_ads_url_link_label') }}"
            master-ads-offset-y-label="{{ trans('strings.view_manage_master_offset_y_label') }}"
            appearance-label="{{ trans('strings.view_manage_appearance_label') }}"
            welcome-appearance-label="{{ trans('strings.view_manage_welcome_type_label') }}"

            route-get-list-type="{{ route('advert.getWelcomeListType') }}"
            list-type-first-menu-name="{{ trans('strings.view_manage_welcome_type_label') }}">
    </manage-application>

@endsection