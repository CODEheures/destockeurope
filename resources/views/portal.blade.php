@extends('layouts.portal')

@section('titlePagePlus')
{{ trans('strings.view_portal_title') }}
@endsection

@section('meta-description')
{{ trans('strings.app_meta_description_portal') }}
@endsection

@section('content')
<!-- main page -->
@include('storeSetter.contents.portal')
<alert-top-fix
        message="{{ trans('strings.alert_top_fix_message') }}"
        sub-message="{{ trans('strings.alert_top_fix_message_sub') }}"
        validity="{{ \Carbon\Carbon::parse('01-03-2018')->toRfc1123String() }}"
        name="offer2"
></alert-top-fix>
<portal
    route-subscribe-news-letter="{{ route('subscribeNewsLetter') }}"
    route-get-highlight="{{ route('advert.getHighlight') }}"
    browser="{{ $browser }}"
    countries="{{ json_encode(\App\Common\LocaleUtils::getListCountries()) }}"
    filter-location-accurate-list="{{ json_encode(\App\Common\GeoManager::$accurate) }}"
    fake-highlight-advert="{{ json_encode(\App\Advert::getFakeAdvert()) }}"
></portal>
@endsection

@section('scripts')
@include('plugins.googleMap.autocomplete.script')
@endsection
