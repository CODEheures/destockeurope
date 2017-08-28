@extends('layouts.app')

@section('content')
    @include('storeSetter.strings.contents.approve-advert-form')
    <approve-advert-form
            route-get-adverts-list="{{ route('advert.listApprove') }}"
            route-advert-approve="{{ route('advert.approve') }}"

            advert-nb-free-picture="{{ config('runtime.nbFreePictures') }}"
    ></approve-advert-form>

@endsection