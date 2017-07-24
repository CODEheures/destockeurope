@extends('layouts.app')

@section('content')
        @include('storeSetter.strings.contents.personnal-list')
        <personnal-list
                route-get-adverts-list="{{ $routeList }}"

                clear-storage="{{ session()->has('clear') ? true : false }}"
                ads-frequency="{{ 0 }}"
                can-get-delegations="{{ \App\Common\PrivilegesUtils::canGetDelegations() }}"
                is-personnal-list="{{ true }}"
                is-delegation="{{ isset($isDelegation) ? $isDelegation : false }}"

                content-header="{{ isset($title) ?  $title : trans('strings.menu_mines') }}"
        ></personnal-list>
@endsection
