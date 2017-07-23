@extends('layouts.app')

@section('headscripts')
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <script>
        window.submitRegister = function(token) {
            document.getElementById("register-form").submit();
        }
    </script>
@endsection

@section('content')
    @include('storeSetter.strings.contents.user-account-register')
    <user-account-register
            route-register="{{ url('/register') }}"
            route-facebook-register="{{ route('social.login', ['provider' => 'facebook']) }}"
            route-twitter-register="{{ route('social.login', ['provider' => 'twitter']) }}"
            route-google-register="{{ route('social.login', ['provider' => 'google']) }}"

            old-name-value="{{ old('name') }}"
            old-email-value="{{ old('email') }}"
            captcha-key="{{ env('GOOGLE_CAPTCHA_SITE_KEY') }}"
    ></user-account-register>



@endsection
