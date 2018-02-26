@extends('layouts.app')

@section('libraries')
    <!-- amcharts -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.12/amcharts.js" integrity="sha256-BWWuudFbBaOHSj0fD+HjZtiEn45PQNl+AzErJ5wCY2g=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.12/gauge.js" integrity="sha256-z9G5ZKRW9tSouHRcdjE+YCwUQJjFX3p19DWySb7dqT0=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.12/serial.js" integrity="sha256-YvUVT2EX5u0GeM1zlOWmoACliati8+d4pKbWONQdrUg=" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/amcharts/3.21.12/themes/light.js" integrity="sha256-/8ddAVEjLXcC1w4acoVN5Xpp308AmeYauva/ws3o8SE=" crossorigin="anonymous"></script>
@endsection
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