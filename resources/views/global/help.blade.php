@extends('layouts.portal')

@section('titlePagePlus')
    {{ trans('strings.view_help_title') }}
@endsection

@section('meta-description')
    {{ trans('strings.app_meta_description_help') }}
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
                        <div class="ui segment">
                            <h1>{!! trans('help.title') !!}</h1>
                            <ol>
                                <li><p>{{ trans('strings.view_help_how_create') }} <a href="https://youtu.be/MaYCkj7vi-k" target="_blank">https://youtu.be/MaYCkj7vi-k</a></p></li>
                            </ol>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection