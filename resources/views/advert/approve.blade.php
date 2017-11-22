@extends('layouts.app')

@section('content')
    @include('storeSetter.contents.approve-advert-form')
    <approve-advert-form
            route-get-adverts-list="{{ route('advert.listApprove') }}"
            route-advert-approve="{{ route('advert.approve') }}"
    ></approve-advert-form>

@endsection