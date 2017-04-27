@extends('layouts.app')

@section('headscripts')
    <script src='https://www.google.com/recaptcha/api.js' async defer></script>
    <script>
        window.submitUnsubscribe = function(token) {
            document.getElementById("unsubscribe-news-letter-form").submit();
        }
    </script>
@endsection

@section('content')
    <h2 class="ui center aligned icon header"><i class="circular remove user icon"></i> {{ trans('strings.menu_unsubscibeNewsLetter') }} </h2>
    <div class="ui centered grid">
        <div class="row">
            <form id="unsubscribe-news-letter-form" class="ui ten wide column form login" method="POST" action="{{ route('postUnsubscribeNewsLetter') }}">
                {{ csrf_field() }}
                <div class="fields">
                    <div class="eight wide field">
                        <label>{{ trans('strings.form_label_email') }}</label>
                        <input id="email" type="email" name="email" placeholder="email" value="{{ old('email') }}" autofocus>
                    </div>
                </div>
                <div class="fields">
                    <div class="field">
                        <button
                                class="g-recaptcha ui primary button"
                                data-sitekey="{{ env('GOOGLE_CAPTCHA_SITE_KEY') }}"
                                data-callback="submitUnsubscribe">
                            {{ trans('strings.menu_unsubscibeNewsLetter') }}
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>



@endsection
