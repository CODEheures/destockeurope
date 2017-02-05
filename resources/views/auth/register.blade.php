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
    <h2 class="ui center aligned icon header"><i
                class="circular add user icon"></i> {{ trans('strings.menu_register') }} </h2>
    <div class="ui centered grid">
        <div class="row">
            <form id="register-form" method="POST" action="{{ url('/register') }}" class="ui ten wide column form register">
                {{ csrf_field() }}
                <div class="fields">
                    <div class="eight wide field">
                        <label>{{ trans('strings.form_label_name') }}</label>
                        <input id="name" type="text" name="name" value="{{ old('name') }}" autofocus>
                    </div>
                    <div class="eight wide field">
                        <label>{{ trans('strings.form_label_email') }}</label>
                        <input id="email" type="email" name="email" value="{{ old('email') }}">
                    </div>
                </div>
                <div class="fields">
                    <div class="eight wide field">
                        <label>{{ trans('strings.form_label_password') }}</label>
                        <input id="password" type="password" name="password">

                    </div>
                    <div class="eight wide field">
                        <label>{{ trans('strings.form_label_confimr_password') }}</label>
                        <input id="password-confirm" type="password" name="password_confirmation">
                    </div>
                </div>
                {{--<div class="g-recaptcha" data-sitekey="{{ env('GOOGLE_CAPTCHA_SITE_KEY') }}"></div>--}}
                <div class="fields spaced-top-2">
                    <div class="field">
                        <button
                                class="g-recaptcha ui primary button"
                                data-sitekey="{{ env('GOOGLE_CAPTCHA_SITE_KEY') }}"
                                data-callback="submitRegister">
                            {{ trans('strings.menu_register') }}
                        </button>
                        {{--<button type="submit" class="ui primary button">--}}
                            {{--{{ trans('strings.menu_register') }}--}}
                        {{--</button>--}}
                    </div>
                </div>
            </form>
        </div>

    </div>
    <div class="ui horizontal divider">{{ trans('strings.divider_register') }} </div>
    <div class="ui centered grid">
        <div class="row social">
            <a href="{{ route('social.login', ['provider' => 'facebook']) }}" class="ui facebook button"><i class="facebook icon"></i> Facebook</a>
            <a href="{{ route('social.login', ['provider' => 'twitter']) }}" class="ui twitter button"><i class="twitter icon"></i> Twitter</a>
            <a href="{{ route('social.login', ['provider' => 'google']) }}" class="ui google plus button"><i class="google plus icon"></i> Google Plus</a>
        </div>

    </div>


@endsection
