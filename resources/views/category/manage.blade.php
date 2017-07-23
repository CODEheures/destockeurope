@extends('layouts.app')

@section('content')
    @include('storeSetter.strings.contents.manage-categories')
    <manage-categories
            route-shift-up-category="{{ route('category.shiftUp') }}"
            route-shift-down-category="{{ route('category.shiftDown') }}"
            route-append-to-category="{{ route('category.appendTo') }}"
    ></manage-categories>

@endsection