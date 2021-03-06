@extends('layouts.app')

@section('titlePagePlus')
@if(isset($categoryName) && isset($countryName) && !is_null($categoryName) && !is_null($countryName))
{{trans('strings.view_home_title_category_country', ['category' => $categoryName, 'country' =>$countryName]) }}
@elseif(isset($categoryName) && !is_null($categoryName))
{{trans('strings.view_home_title_category', ['category' => $categoryName]) }}
@elseif(isset($countryName) && !is_null($countryName))
{{trans('strings.view_home_title_country', ['country' =>$countryName]) }}
@else
{{ trans('strings.view_home_title') }}
@endif
@endsection

@section('meta-description')
@if(isset($categoryName) && isset($countryName) && !is_null($categoryName) && !is_null($countryName))
{{trans('strings.app_meta_description_home_category_country', ['category' => $categoryName, 'country' =>$countryName]) }}
@elseif(isset($categoryName) && !is_null($categoryName))
{{trans('strings.app_meta_description_home_category', ['category' => $categoryName]) }}
@elseif(isset($countryName) && !is_null($countryName))
{{trans('strings.app_meta_description_home_country', ['country' =>$countryName]) }}
@else
{{ trans('strings.app_meta_description_home') }}
@endif
@endsection
@section('opengraph')
<meta property="fb:app_id" content="{{ env('FACEBOOK_CLIENT_ID') }}">
<meta property="og:site_name" content="{{ config('app.name') }}">
<meta property="og:title" content="{{ trans('strings.view_home_title') }}">
<meta property="og:description" content="{{ trans('strings.app_meta_description_home') }}">
<meta property="og:image" content="{{ asset('/images/facebookShare2.jpg') }}">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="675">
<meta property="og:url" content="{{ route('home', ['lang' => \Illuminate\Support\Facades\App::getLocale()]) }}">
<meta property="og:type" content="website">
@endsection

@section('asyncScriptsList')
    @include('plugins.googleMap.autocomplete.script')
@endsection

@section('content')
@include('storeSetter.contents.welcome1')
<welcome1
    route-bookmark-add="{{ route('bookmark.add') }}"
    route-bookmark-remove="{{ route('bookmark.remove') }}"
    route-notifications-exist-in="{{ route('notification.existIn') }}"
    route-notifications-add="{{ route('notification.add') }}"
    route-notifications-remove="{{ route('notification.remove') }}"
    route-get-highlight="{{ route('advert.getHighlight') }}"

    masterads-route-image-server = "{{ route('imageServer') }}"
    masterads-is-active="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && isset($masterAdsControllerFlag) ? $masterAdsControllerFlag : false }}"
    masterads-url-img="{{config('runtime.urlMasterAds')}}"
    masterads-url-redirect="{{ config('runtime.urlLinkMasterAds') }}"
    masterads-offset-y-main-container="{{ config('runtime.offsetYMasterAds') }}"
    masterads-width="{{ filter_var(config('runtime.masterAds'), FILTER_VALIDATE_BOOLEAN) && isset($masterAdsControllerFlag) ? config('runtime.widthUrlMasterAds') : 0 }}"
    filter-location-accurate-list="{{ json_encode(\App\Common\GeoManager::$accurate) }}"
    ads-frenquency="{{ config('runtime.adsFrequency') }}"
    fake-highlight-advert="{{ json_encode(\App\Advert::getFakeAdvert()) }}"
></welcome1>
@endsection
