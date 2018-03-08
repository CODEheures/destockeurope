@extends('layouts.portal')

@section('titlePagePlus')
{{ trans('strings.view_portal_title') }}
@endsection

@section('meta-description')
{{ trans('strings.app_meta_description_portal') }}
@endsection

@section('asyncScriptsList')
    @include('plugins.googleMap.autocomplete.script')
@endsection

@section('content')
<!-- main page -->
@include('storeSetter.contents.portal')
<portal
    route-subscribe-news-letter="{{ route('subscribeNewsLetter') }}"
    route-get-highlight="{{ route('advert.getHighlight') }}"
    browser="{{ $browser }}"
    countries="{{ json_encode(\App\Common\LocaleUtils::getListCountries()) }}"
    filter-location-accurate-list="{{ json_encode(\App\Common\GeoManager::$accurate) }}"
    fake-highlight-advert="{{ json_encode(\App\Advert::getFakeAdvert()) }}"
></portal>
@endsection
