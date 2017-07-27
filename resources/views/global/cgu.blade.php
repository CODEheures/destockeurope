@extends('layouts.portal')

@section('titlePagePlus')
    {{ trans('strings.view_cgu_title') }}
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
                            <h1 class="ui header">{!! trans('cg.u.title') !!}</h1>
                            {!! trans('cg.tempo') !!}

                            {!! trans('cg.definitions') !!}

                            {!! trans('cg.u.object') !!}

                            {!! trans('cg.u.accept') !!}

                            {!! trans('cg.u.using') !!}

                            {!! trans('cg.u.diffusionsRulesTitle') !!}
                            {!! trans('cg.diffusionsRules') !!}

                            {!! trans('cg.u.advertisserEngagment') !!}

                            {!! trans('cg.u.propertyIntellect') !!}

                            {!! trans('cg.u.responsabilityDestock') !!}

                            {!! trans('cg.u.cookies') !!}

                            {!! trans('cg.u.modification') !!}

                            {!! trans('cg.u.juridiction') !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection