@extends('layouts.app')

@section('content')
    @include('storeSetter.strings.contents.review-for-payment')
    <review-for-payment
            route-paypal-choice="{{ route('advert.payByPaypal', ['invoiceId' => $invoice->id]) }}"
            route-card-choice="{{ route('advert.payByCard', ['invoiceId' => $invoice->id]) }}"
            route-prices="{{ route('prices') }}"

            invoice="{{ json_encode($invoice) }}"
            cards-types="{{ json_encode($listCardTypes) }}"
            url-img-paypal-disabled="{{ asset('/images/frenchPayButton2_disable.png') }}"
            url-img-paypal-enabled="{{ asset('/images/frenchPayButton2.png') }}"
    ></review-for-payment>

@endsection