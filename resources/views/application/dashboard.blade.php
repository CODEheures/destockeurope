@extends('layouts.app')

@section('css')
    @include('plugins.amcharts.css')
@endsection

@section('content')

    <dashboard-admin
            route-clean-app = "{{ route('application.cleanApp') }}"
            route-transfert-medias="{{ route('application.transfertMedias') }}"
            route-get-stats="{{ route('application.getStats') }}"
            route-progress-transfert-medias="{{ route('application.progressTransfertMedias') }}"

            content-header="{{ trans('strings.menu_dashboard') }}"
            load-error-message="{{ trans('strings.view_all_error_load_message') }}"
            dashboard-label="{{ trans('strings.view_manage_dashboard_label') }}"
            menu-clean-app-label="{{ trans('strings.menu_cleanApp') }}"
            menu-transfert-medias-label="{{ trans('strings.menu_transfertMedias') }}"
            size-local-file-label="{{ trans('strings.dashboard_size_local_files_label') }}"
            size-distant-file-label="{{ trans('strings.dashboard_size_distant_files_label') }}"
            count-files-title="{{ trans('strings.dashboard_count_files_title') }}"
            count-local-files-label="{{ trans('strings.dashboard_count_local_files_label') }}"
            count-distant-files-label="{{ trans('strings.dashboard_count_distant_files_label') }}"
            mega-bytes-label="{{ trans('strings.dashboard_megaBytes_label') }}"
            stats-label="{{ trans('strings.dashboard_stats_label') }}"
            graph-advert-title="{{ trans('strings.dashboard_graph_adverts_title') }}"
            graph-valid-adverts-label="{{ trans('strings.dashboard_graph_valid_adverts_label') }}"
            graph-invalid-adverts-label="{{ trans('strings.dashboard_graph_invalid_adverts_label') }}"
            graph-waiting-adverts-label="{{ trans('strings.dashboard_graph_waiting_adverts_label') }}"
            graph-costs-title="{{ trans('strings.dashboard_graph_costs_title') }}"
            graph-sum-costs-label="{{ trans('strings.dashboard_graph_sum_costs_label') }}"
            graph-avg-costs-label="{{ trans('strings.dashboard_graph_avg_costs_label') }}"
            graph-views-title="{{ trans('strings.dashboard_graph_views_title') }}"
    ></dashboard-admin>

@endsection

@section('scripts')
    @include('plugins.amcharts.scripts')
@endsection