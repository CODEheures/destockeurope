@extends('layouts.errors')

@section('icon')
    heartbeat
@endsection

@section('titles')
    @foreach(config('codeheuresUtils.availableLocales') as $lang)
        @php app()->setLocale($lang) @endphp
        @if($loop->first)
            <div class="side active">
                {{ trans('strings.view_503_title') }}
            </div>
        @else
            <div class="side">
                {{ trans('strings.view_503_title') }}
            </div>
        @endif
    @endforeach
@endsection

@section('messages')
    @foreach(config('codeheuresUtils.availableLocales') as $lang)
        @php app()->setLocale($lang) @endphp
        @if($loop->first)
            <p class="side active">
                {{ trans('strings.view_503_message') }}
            </p>
        @else
            <p class="side">
                {{ trans('strings.view_503_message') }}
            </p>
        @endif
    @endforeach
@endsection