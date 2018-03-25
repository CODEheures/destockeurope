@extends('layouts.portal')

@section('titlePagePlus')
{{ trans('strings.view_cgv_title') }}
@endsection

@section('meta-description')
{{ trans('strings.app_meta_description_cgv') }}
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
                        <h1 class="ui header">{{ trans('policies.title') }}</h1>

                        {!! trans('policies.explanations') !!}

                        {!! trans('policies.CollectedList') !!}

                        {!! trans('policies.Uses') !!}

                        {!! trans('policies.Destinations') !!}

                        {!! trans('policies.manage') !!}

                        {!! trans('policies.conservation') !!}

                        {!! trans('policies.security') !!}

                        {!! trans('policies.cookies') !!}

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection