<store-strings-setter
        strings="{{ json_encode([
                        'key' => 'dashboard-admin',
                        'values' => [
                                'contentHeader' => trans('strings.menu_dashboard'),
                                'loadErrorMessage' => trans('strings.view_all_error_load_message'),
                                'dashboardLabel' => trans('strings.view_manage_dashboard_label'),
                                'menuCleanAppLabel' => trans('strings.menu_cleanApp'),
                                'statsLabel' => trans('strings.dashboard_stats_label'),
                                'logsLabel' => trans('strings.dashboard_logs_label'),
                                'graphAdvertTitle' => trans('strings.dashboard_graph_adverts_title'),
                                'graphValidAdvertsLabel' => trans('strings.dashboard_graph_valid_adverts_label'),
                                'graphInvalidAdvertsLabel' => trans('strings.dashboard_graph_invalid_adverts_label'),
                                'graphWaitingAdvertsLabel' => trans('strings.dashboard_graph_waiting_adverts_label'),
                                'graphCostsTitle' => trans('strings.dashboard_graph_costs_title'),
                                'graphSumCostsLabel' => trans('strings.dashboard_graph_sum_costs_label'),
                                'graphAvgCostsLabel' => trans('strings.dashboard_graph_avg_costs_label'),
                                'graphViewsTitle' => trans('strings.dashboard_graph_views_title')
                        ]
                ])}}"
></store-strings-setter>
@include('storeSetter.strings.generics.chart-load-infos')