@extends('layouts.app')

@section('css')

@endsection

@section('content')
    @include('storeSetter.contents.dashboard-admin')
    <dashboard-admin
            route-clean-app = "{{ route('application.cleanApp') }}"
            route-get-stats="{{ route('application.getStats') }}"
            load-infos="{{ json_encode($loadInfos) }}"
    ></dashboard-admin>

@endsection

@section('scripts')

@endsection