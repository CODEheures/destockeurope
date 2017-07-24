@extends('layouts.app')

@section('content')
    @include('storeSetter.strings.contents.manage-invoices')
    <manage-invoices
            route-get-invoices-list="{{ route('admin.invoice.list') }}"
            clear-storage="{{ session()->has('clear') ? true : false }}"
    ></manage-invoices>

@endsection