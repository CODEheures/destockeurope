@extends('layouts.portal')

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
                            <h1 class="ui header">{{ trans('cg.v.title') }}</h1>
                            {!! trans('cg.tempo') !!}

                            {!! trans('cg.definitions') !!}

                            {!! trans('cg.v.object') !!}

                            {!! trans('cg.v.accept') !!}

                            {!! trans('cg.v.ordering') !!}

                            {!! trans('cg.v.description') !!}
                            {!! trans('cg.v.options.urgent') !!}
                            {!! trans('cg.v.options.toNegociate') !!}
                            {!! trans('cg.v.options.video') !!}
                            {!! trans('cg.v.options.photos1') !!}
                            @for($i = config('runtime.nbFreePictures')+1 ; $i <= config('runtime.nbMaxPictures'); $i++)
                                {!! trans('cg.v.options.photos2', ['compt' => $i, 'price' => \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostPictures($i),'EUR',true)]) !!}
                            @endfor
                            {!! trans('cg.v.options.photos3') !!}
                            {!! trans('cg.v.options.edition') !!}
                            {!! trans('cg.v.options.renew') !!}
                            {!! trans('cg.v.options.backToTop') !!}
                            {!! trans('cg.v.options.highlight1') !!}
                            <?php $cases = [0,1,2,3,5,10,20] ?>
                            @foreach($cases as $case)
                                {!! trans('cg.v.options.highlight2', ['case' => $case, 'price' => \App\Common\MoneyUtils::getPriceWithDecimal(\App\Common\CostUtils::getCostIsHighlight(true, $case),'EUR', true)]) !!}
                            @endforeach
                            {!! trans('cg.v.options.highlight3') !!}

                            {!! trans('cg.v.major') !!}

                            {!! trans('cg.v.modifications') !!}

                            {!! trans('cg.v.juridiction') !!}

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection