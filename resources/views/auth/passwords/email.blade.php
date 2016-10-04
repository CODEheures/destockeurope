@extends('layouts.app')

<!-- Main Content -->
@section('content')
    <h2 class="ui center aligned icon header"><i class="circular erase icon"></i> {{ trans('strings.title_reset_password') }} </h2>
    <div class="ui centered grid">
        <form class="ui ten wide column form login" method="POST" action="{{ url('/password/email') }}">
            {{ csrf_field() }}
            <div class="fields">
                <div class="eight wide field">
                    <label>{{ trans('strings.form_label_email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="fields">
                <div class="field">
                    <button type="submit" class="ui primary button">
                        {{ trans('strings.link_send_reset_link') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
