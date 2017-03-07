@extends('layouts.portal')

@section('content')
    <!-- banner -->
    <div class="ui grid">
        <div class="four wide tablet only four wide computer only column"></div>
        <div class="twelve wide computer only column">
            <div class="ui leaderboard test ad" data-text="728 x 90"></div>
        </div>
        <div class="twelve wide tablet only column">
            <div class="ui banner test ad" data-text="banner"></div>
        </div>
        <div class="sixteen wide mobile only column">
            <div class="ui half banner test centered ad" data-text="Half Banner"></div>
        </div>
    </div>

    <!-- main page -->
    <div class="ui grid">
        <div class="four wide tablet only four wide computer only column">
            <div class="ui small rectangle test ad " data-text="180 x 150"></div>
            <div class="ui small rectangle test ad spaced-top-2" data-text="180 x 150"></div>
            <!--<div class="ui wide skyscraper test ad welcome-ads" data-text="160 x 600"></div>-->
        </div>
        <div class="sixteen wide mobile twelve wide tablet twelve wide computer column">
            <div class="ui segment">
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <div class="ui blue inverted newsletter segment">
                            <div class="ui top right attached yellow label">{{trans('strings.view_contact_title')}}</div>
                            <p>{{ trans('strings.view_contact_description') }}</p>
                            <form class="ui inverted form" accept-charset="UTF-8" action="{{ route('contactPost') }}"  method="post">
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
                                <button class="ui button" type="submit">{{ trans('strings.view_contact_button_label') }}</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
