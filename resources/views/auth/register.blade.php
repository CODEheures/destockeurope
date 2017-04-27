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
    <user-account-register
            route-register="{{ url('/register') }}"
            route-facebook-register="{{ route('social.login', ['provider' => 'facebook']) }}"
            route-twitter-register="{{ route('social.login', ['provider' => 'twitter']) }}"
            route-google-register="{{ route('social.login', ['provider' => 'google']) }}"

            old-name-value="{{ old('name') }}"
            old-email-value="{{ old('email') }}"
            captcha-key="{{ env('GOOGLE_CAPTCHA_SITE_KEY') }}"

            content-header="{{ trans('strings.menu_register') }}"
            form-name-label="{{ trans('strings.form_label_name') }}"
            form-email-label="{{ trans('strings.form_label_email') }}"
            form-password-label="{{ trans('strings.form_label_password') }}"
            form-password-confirm-label="{{ trans('strings.form_label_confimr_password') }}"
            form-cgu-check-label="{{ trans('strings.auth_register_cgu_check', ['link' => route('cgu')]) }}"
            form-newsletter-check-label="{{ trans('strings.auth_register_newsletter_check') }}"
            form-button-label="{{ trans('strings.menu_register') }}"
            divider-label="{{ trans('strings.divider_register') }}"
    ></user-account-register>



@endsection
