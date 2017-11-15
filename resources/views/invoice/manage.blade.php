@extends('layouts.app')

@section('content')
    @include('storeSetter.contents.manage-invoices')
    <manage-invoices
            route-get-invoices-list="{{ route('admin.invoice.list') }}"
    ></manage-invoices>

@endsection