@extends('layouts.app')

@section('content')
        @include('storeSetter.contents.personnal-list')
        <personnal-list
                route-bookmark-add="{{ route('bookmark.add') }}"
                route-bookmark-remove="{{ route('bookmark.remove') }}"

                reload-advert-on-unbookmark-success="{{ true }}"
                ads-frequency="{{ 0 }}"

                content-header="{{ trans('strings.menu_bookmarks') }}"


        ></personnal-list>
@endsection
