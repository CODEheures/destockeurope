@extends('layouts.portal')

@section('content')
    <!-- main page -->
    @include('storeSetter.strings.contents.portal')
    <portal
        route-subscribe-news-letter="{{ route('subscribeNewsLetter') }}"
        browser="{{ $browser }}"
        countries="{{ json_encode(\App\Common\LocaleUtils::getListCountries()) }}"
        filter-location-accurate-list="{{ json_encode(\App\Common\GeoManager::$accurate) }}"
    ></portal>
@endsection

@section('scripts')
    @include('plugins.googleMap.autocomplete.script')
@endsection
