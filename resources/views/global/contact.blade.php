@extends('layouts.portal')

@section('titlePagePlus')
{{ trans('strings.view_contact_title') }}
@endsection

@section('meta-description')
{{ trans('strings.app_meta_description_contact') }}
@endsection

@section('headscripts')
<script src='https://www.google.com/recaptcha/api.js' async defer></script>
<script>
    window.submitMessage = function(token) {
        document.getElementById("message-form").submit();
    }
</script>
@endsection

@section('content')
<!-- main page -->
<div class="ui grid">
    <div class="four wide tablet only four wide computer only column">
        <double-square
                :centered="true"
        ></double-square>
    </div>
    <div class="sixteen wide mobile twelve wide tablet twelve wide computer column">
        <div class="ui segment">
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div class="ui blue inverted newsletter segment">
                        <div class="ui top right attached yellow label">{{trans('strings.view_contact_header')}}</div>
                        <p>{{ trans('strings.view_contact_description') }}</p>
                        <form id="message-form" class="ui inverted form" accept-charset="UTF-8" action="{{ route('contactPost') }}"  method="post">
                            {{ csrf_field() }}
                            <div class="required field">
                                <input type="email" name="email" placeholder="{{ trans('strings.form_label_email') }}">
                            </div>
                            <div class="required field">
                                <input type="text" name="name" placeholder="{{ trans('strings.form_label_name') }}">
                            </div>
                            <div class="required field">
                                <textarea name="message">{{ trans('strings.form_label_message_input') }}</textarea>
                            </div>
                            <button
                                    class="g-recaptcha ui button"
                                    data-sitekey="{{ env('GOOGLE_CAPTCHA_SITE_KEY') }}"
                                    data-callback="submitMessage">
                                {{ trans('strings.view_contact_button_label') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
