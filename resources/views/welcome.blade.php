@extends('layouts.app')

@section('titlePagePlus')
{{ isset($countryName) ? trans('strings.view_home_title_with_country', ['country' =>$countryName]) : trans('strings.view_home_title_without_country')  }}
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
                fake-highlight-advert="{{ json_encode($fakeHighlightAdvert) }}"
        ></welcome1>
@endsection

@section('scripts')
    @include('plugins.googleMap.autocomplete.script')
@endsection
