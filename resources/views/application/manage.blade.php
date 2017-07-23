@extends('layouts.app')

@section('content')
    @include('storeSetter.strings.contents.manage-application')
    <manage-application
            route-parameters="{{ route('application.parameters') }}"
            route-test-is-picture="{{ route('utils.isPicture') }}"
            route-get-list-type="{{ route('advert.getWelcomeListType') }}"
    ></manage-application>

@endsection