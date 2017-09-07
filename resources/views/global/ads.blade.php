@extends('layouts.portal')

@section('titlePagePlus')
{{ trans('strings.view_ads_title') }}
@endsection

@section('meta-description')
{{ trans('strings.app_meta_description_ads') }}
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
                        <h1>{!! trans('ads.title') !!}</h1>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection