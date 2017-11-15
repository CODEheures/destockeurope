@extends('layouts.app')

@section('content')
        @include('storeSetter.contents.personnal-list')
        <personnal-list
                ads-frequency="{{ 0 }}"
                can-get-delegations="{{ \App\Common\PrivilegesUtils::canGetDelegations() }}"
                is-personnal-list="{{ true }}"
                is-delegation="{{ isset($isDelegation) ? $isDelegation : false }}"

                content-header="{{ isset($title) ?  $title : trans('strings.menu_mines') }}"
        ></personnal-list>
@endsection
