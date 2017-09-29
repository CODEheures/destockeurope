@extends('layouts.app')

@section('headscripts')
    <!-- Load PayPal's checkout.js Library. -->
    <script src="https://www.paypalobjects.com/api/checkout.js" data-version-4 log-level="warn"></script>

    <!-- Load the client component. -->
    <script src="https://js.braintreegateway.com/web/3.22.2/js/client.min.js"></script>

    <!-- Load the PayPal Checkout component. -->
    <script src="https://js.braintreegateway.com/web/3.22.2/js/paypal-checkout.min.js"></script>

    <!-- Load the PayPal Hosted fields component. -->
    <script src="https://js.braintreegateway.com/web/3.22.2/js/hosted-fields.min.js"></script>

    <!-- Load the 3D Secure component. -->
    <script src="https://js.braintreegateway.com/web/3.22.2/js/three-d-secure.min.js"></script>

    <!-- Load the Paypal Data Collector -->
    <script src="https://js.braintreegateway.com/web/3.22.2/js/data-collector.min.js"></script>
@endsection

@section('content')
    @include('storeSetter.contents.review-for-payment')
    <review-for-payment
            route-post-nonce="{{ route('advert.postNonce', ['invoiceId' => $invoice->id]) }}"
            route-prices="{{ route('prices') }}"
            invoice="{{ json_encode($invoice) }}"
            mode="{{ $mode }}"
            client-token="{{ $clientToken }}"
    ></review-for-payment>

@endsection