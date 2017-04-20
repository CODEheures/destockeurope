@extends('layouts.portal')

@section('content')
    <!-- main page -->
    <portal
        route-home="{{ route('home') }}"
        route-subscribe-news-letter="{{ route('subscribeNewsLetter') }}"
        browser="{{ $browser }}"
        countries="{{ json_encode(\App\Common\LocaleUtils::getListCountries()) }}"
        see-advert-label="{{ trans('strings.view_portal_button_filter_label') }}"
        news-letter-title="{{ trans('strings.view_portal_newsletter_title') }}"
        news-letter-description="{{ trans('strings.view_portal_newsletter_description') }}"
        news-letter-button-label="{{ trans('strings.menu_subscribe') }}"
        news-letter-email-place-holder="{{ trans('strings.form_label_email') }}"
        news-letter-phone-place-holder="{{ trans('strings.form_label_phone') }}"
        news-letter-name-place-holder="{{ trans('strings.form_label_name') }}"

        filter-location-accurate-list="{{ json_encode(\App\Common\GeoManager::$accurate) }}"
        filter-location-place-holder="{{ trans('strings.form_input_location_label') }}"
    ></portal>
@endsection

@section('scripts')
    @include('plugins.googleMap.autocomplete.script')
@endsection
