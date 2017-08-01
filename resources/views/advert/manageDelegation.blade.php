@extends('layouts.app')

@section('content')
        @include('storeSetter.strings.contents.manage-delegation')
        <manage-delegation
                advert = "{{ json_encode($advert) }}"

                can-get-delegations="{{ \App\Common\PrivilegesUtils::canGetDelegations() }}"
                is-personnal-list="{{ true }}"
                is-delegation="{{ true }}"

                content-header="{{ isset($title) ?  $title : trans('strings.menu_mines') }}"
        ></manage-delegation>
@endsection
