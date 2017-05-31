@extends('layouts.app')

<!-- Main Content -->
@section('content')
    <h2 class="ui center aligned icon header"><i class="mail outline icon"></i> {{ trans('strings.title_change_email') }} </h2>
    <div class="ui centered grid">
        <form class="ui ten wide column form login" method="POST" action="{{ route('changeEmailPost') }}">
            {{ csrf_field() }}
            <div class="fields">
                <div class="eight wide field">
                    <label>{{ trans('strings.form_label_new_email') }}</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}">
                </div>
            </div>
            <div class="fields">
                <div class="field">
                    <button type="submit" class="ui primary button">
                        {{ trans('strings.link_send_validation_link') }}
                    </button>
                </div>
            </div>
        </form>
    </div>
@endsection
