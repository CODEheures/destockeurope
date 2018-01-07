@extends('layouts.app')

@section('content')
    @include('storeSetter.contents.create-invoice-for-delegation')
    <create-invoice-for-delegation
            route-post-create="{{ route('admin.invoice.postForDelegation') }}"
    ></create-invoice-for-delegation>
@endsection