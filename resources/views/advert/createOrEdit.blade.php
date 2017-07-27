@extends('layouts.app')

@section('titlePagePlus')
{{ trans('strings.view_advert_create_title')  }}
@endsection

@section('content')
    @include('storeSetter.strings.contents.createOrEditAdvert')
    <create-or-edit-advert-form
            route-advert-form-post="{{ $route }}"
            route-post-tempo-picture="{{ route('picture.post',['type'=>\App\Common\PicturesManager::TYPE_TEMPO_LOCAL]) }}"
            route-get-list-tempo-thumbs="{{ route('picture.listThumbs', ['type'=>\App\Common\PicturesManager::TYPE_TEMPO_LOCAL]) }}"
            route-get-tempo-thumb="{{ route('picture.thumb', ['type' => \App\Common\PicturesManager::TYPE_TEMPO_LOCAL])}}"
            route-get-tempo-normal="{{ route('picture.normal', ['type' => \App\Common\PicturesManager::TYPE_TEMPO_LOCAL])}}"
            route-del-tempo-picture="{{ route('picture.destroyTempo')}}"
            route-get-cost="{{ route('advert.cost')}}"
            route-prices="{{ route('prices') }}"
            route-get-list-type="{{ route('advert.getListType') }}"
            route-get-video-post-ticket="{{ route('videos.ticket') }}"
            route-del-tempo-video="{{ route('videos.delTempo') }}"
            route-get-status-video="{{ route('videos.status') }}"

            old="{{ count(old())>0 ? 1 : 0 }}"
            advert-form-photo-nb-free-picture="{{ config('runtime.nbFreePictures') }}"
            max-files="{{ config('runtime.nbMaxPictures') }}"
            form-title-min-valid="{{ config('db_limits.adverts.minTitle') }}"
            form-title-max-valid="{{ config('db_limits.adverts.maxTitle') }}"
            form-description-min-valid="{{ config('db_limits.adverts.minDescription') }}"
            form-description-max-valid="{{ config('db_limits.adverts.maxDescription') }}"
            is-delegation="{{ isset($user) ? $user->isSupplier : false }}"
            edit-advert="{{ isset($editAdvert) ? json_encode($editAdvert) : '' }}"
            geoloc-init-lat="{{ isset($lat) ? $lat : null }}"
            geoloc-init-lng="{{ isset($lng) ? $lng : null }}"
            max-video-file-size="{{ env('VIDEO_MAX_SIZE_MB')*1024*1024 }}"
            session-video-id="{{ session()->has('videoId') ? session('videoId') : null }}"
    ></create-or-edit-advert-form>

@endsection

@section('scripts')
    @include('plugins.googleMap.map.script')
@endsection