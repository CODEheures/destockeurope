@extends('layouts.app')

@section('content')
    @include('storeSetter.contents.manage-users')
    <manage-users
            route-get-users-list="{{ route('admin.user.list') }}"
            clear-storage="{{ session()->has('clear') ? true : false }}"
    ></manage-users>

@endsection