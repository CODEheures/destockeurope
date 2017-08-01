@extends('layouts.errors')


@section('icon')
    help
@endsection

@section('titles')
    @php $initiallang_rotatry_keywords = app()->getLocale() @endphp
    @foreach(config('codeheuresUtils.availableLocales') as $lang)
        @php app()->setLocale($lang) @endphp
        @if($lang == $initiallang_rotatry_keywords)
            <div class="side active">
                {{ trans('strings.view_404_title') }}
            </div>
        @else
            <div class="side">
                {{ trans('strings.view_404_title') }}
            </div>
        @endif
    @endforeach
    @php app()->setLocale($initiallang_rotatry_keywords) @endphp
@endsection

@section('messages')
    @php $initiallang_rotatry_keywords = app()->getLocale() @endphp
    @foreach(config('codeheuresUtils.availableLocales') as $lang)
        @php app()->setLocale($lang) @endphp
        @if($lang == $initiallang_rotatry_keywords)
            <p class="side active">
                {{ trans('strings.view_404_message') }}
            </p>
        @else
            <p class="side">
                {{ trans('strings.view_404_message') }}
            </p>
        @endif
    @endforeach
    @php app()->setLocale($initiallang_rotatry_keywords) @endphp
@endsection