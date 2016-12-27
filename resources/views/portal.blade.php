@extends('layouts.portal')

@section('content')
    <!-- banner -->
    <div class="ui grid">
        <div class="four wide tablet only four wide computer only column"></div>
        <div class="twelve wide computer only column">
            <div class="ui leaderboard test ad" data-text="Leaderboard"></div>
        </div>
        <div class="twelve wide tablet only column">
            <div class="ui banner test ad" data-text="banner"></div>
        </div>
        <div class="sixteen wide mobile only column">
            <div class="ui half banner test centered ad" data-text="Half Banner"></div>
        </div>
    </div>

    <!-- main page -->
    <portal
        route-home="{{ route('home') }}"
        route-subscribe-news-letter="{{ route('subscribeNewsLetter') }}"
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
