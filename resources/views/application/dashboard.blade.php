@extends('layouts.app')

@section('css')
    {{--@include('plugins.amcharts.css')--}}
@endsection

@section('content')
    @include('storeSetter.strings.contents.dashboard-admin')
    <dashboard-admin
            route-clean-app = "{{ route('application.cleanApp') }}"
            route-get-stats="{{ route('application.getStats') }}"
            load-infos="{{ json_encode($loadInfos) }}"
    ></dashboard-admin>

@endsection

@section('scripts')
    {{--@include('plugins.amcharts.scripts')--}}
@endsection