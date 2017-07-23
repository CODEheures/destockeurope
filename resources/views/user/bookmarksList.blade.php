@extends('layouts.app')

@section('content')
        @include('storeSetter.strings.contents.personnal-list')
        <personnal-list
                route-get-adverts-list="{{ route('advert.bookmarks') }}"
                route-bookmark-add="{{ route('bookmark.add') }}"
                route-bookmark-remove="{{ route('bookmark.remove') }}"

                reload-advert-on-unbookmark-success="{{ true }}"
                ads-frequency="{{ 0 }}"

                content-header="{{ trans('strings.menu_bookmarks') }}"


        ></personnal-list>
@endsection
