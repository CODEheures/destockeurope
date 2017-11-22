@extends('layouts.app')

@section('content')
        @include('storeSetter.contents.manage-delegation')
        <manage-delegation
                advert = "{{ json_encode($advert) }}"
                can-get-delegations="{{ \App\Common\PrivilegesUtils::canGetDelegations() }}"
                content-header="{{ isset($title) ?  $title : trans('strings.menu_mines') }}"
        ></manage-delegation>
@endsection
