@extends('layouts.app')

@section('content')
    <h2 class="ui center aligned icon header"><i class="circular refresh icon"></i> {{ trans('strings.title_new_password') }} </h2>
    <div class="ui centered grid">
        <form class="ui ten wide column form login" method="POST" action="{{ url('/password/reset') }}">
            {{ csrf_field() }}
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="fields">
                <div class="eight wide field">
                    <label>{{ trans('strings.form_label_email') }}</label>
                    <input id="email" type="email" name="email" value="{{ $email or old('email') }}" autofocus>
                </div>
            </div>
            <div class="fields">
                <div class="eight wide field">
                    <label>{{ trans('strings.form_label_password') }}</label>
                    <input id="password" type="password" name="password">
                </div>
                <div class="eight wide field">
                    <label>{{ trans('strings.form_label_confimr_password') }}</label>
                    <input id="password_confirmation" type="password" name="password_confirmation">
                </div>
            </div>
            <div class="fields">
                <div class="field">
                    <button type="submit" class="ui primary button">
                        {{ trans('strings.form_button_new_password') }}
                    </button>
                </div>
            </div>
        </form>
    </div>


@endsection
