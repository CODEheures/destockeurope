@extends('layouts.app')

@section('content')
    <h2 class="ui center aligned icon header"><i class="circular sign in icon"></i> {{ trans('strings.menu_login') }} </h2>
    <div class="ui centered grid">
        <div class="row">
            <form class="ui ten wide column form login" method="POST" action="{{ url('/login') }}">
                {{ csrf_field() }}
                <div class="fields">
                    <div class="eight wide field">
                        <label>{{ trans('strings.form_label_email') }}</label>
                        <input id="email" type="email" name="email" placeholder="email" value="{{ old('email') }}" autofocus>
                    </div>
                    <div class="eight wide field">
                        <label>{{ trans('strings.form_label_password') }}</label>
                        <input id="password" type="password" name="password" >
                    </div>
                </div>
                <div class="fields">
                    <div class="inline field">
                        <div class="ui toggle checkbox">
                            <input type="checkbox" tabindex="0" class="hidden" name="remember">
                            <label>{{ trans('strings.form_label_remember_me') }}</label>
                        </div>
                    </div>
                </div>
                <div class="fields">
                    <div class="field">
                        <button type="submit" class="ui primary button">
                            {{ trans('strings.menu_login') }}
                        </button>
                        <a class="btn btn-link" href="{{ url('/password/reset') }}">
                            {{ trans('strings.link_forgot_password') }}?
                        </a>
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
