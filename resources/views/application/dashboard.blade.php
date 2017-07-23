@extends('layouts.app')

@section('css')
    @include('plugins.amcharts.css')
@endsection

@section('content')
    @include('storeSetter.strings.contents.dashboard-admin')
    <dashboard-admin
            route-clean-app = "{{ route('application.cleanApp') }}"
            route-transfert-medias="{{ route('application.transfertMedias') }}"
            route-get-stats="{{ route('application.getStats') }}"
            route-progress-transfert-medias="{{ route('application.progressTransfertMedias') }}"
    ></dashboard-admin>

@endsection

@section('scripts')
    @include('plugins.amcharts.scripts')
@endsection