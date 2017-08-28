<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="column">
            <h2 class="ui header">{{ strings.contentHeader }}</h2>
        </div>
        <div class="column">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <div class="ui form">
                <h4 class="ui horizontal divider header"><i class="dashboard icon"></i> {{ strings.dashboardLabel }} </h4>
                <div class="ui grid">
                    <div class="sixteen wide column" v-for="loadInfos in dataLoadInfos">
                        <div class="ui segment">
                            <chart-load-infos
                                    :load-infos="loadInfos"
                            ></chart-load-infos>
                        </div>

                    </div>
                </div>
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <button :class="dataCleanLoading ? 'ui primary loading button' : 'ui primary button' "
                                v-on:click="cleanApp">{{ strings.menuCleanAppLabel }}</button>
                    </div>
                </div>
                <h4 class="ui horizontal divider header"><i class="line chart icon"></i> {{ strings.statsLabel }} </h4>
                <div class="ui grid">
                    <div class="sixteen wide column">
                        <div id="graphAdverts" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
                    </div>
                    <div class="sixteen wide column">
                        <div id="graphViews" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
                    </div>
                    <div class="sixteen wide column">
                        <div id="graphCosts" style="width: 100%; height: 400px; background-color: #FFFFFF;"></div>
                    </div>
                </div>
                <h4 class="ui horizontal divider header"><i class="newspaper icon"></i> {{ strings.logsLabel }} </h4>
                <div class="ui grid">
                    <template v-for="(log, index) in dataStats.logs">
                        <p>{{ index }}</p>
                        <div class="sixteen wide column">
                            <table class="ui unstackable celled fixed table">
                                <thead>
                                <tr>
                                    <th v-for="header in log[0]">{{ header }}</th>
                                </tr>
                                </thead>
                                <tbody>
                                    <tr v-for="(line,index) in log" v-if="index!=0">
                                        <td v-for="(value,index) in line">{{ index == 0 ? getFormatDate(value) : value }}</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </template>

                </div>
            </div>
        </div>
    </div>
</template>


<script>
    export default {
        directives: {focus: focus},
        props: {
            //vue routes
            routeCleanApp: String,
            routeGetStats: String,
            //vue vars
            loadInfos: String
        },
        data: () => {
            return {
                strings: {},
                properties: {},
                isLoaded: false,
                sendMessage: false,
                typeMessage: '',
                message: '',
                dataCleanLoading: false,
                dataStatsLoading : false,
                dataStats: {},
                dataLoadInfos: []
            };
        },
        mounted () {
            this.strings = this.$store.state.strings['dashboard-admin'];
            this.properties = this.$store.state.properties['global'];
            this.dataLoadInfos = JSON.parse(this.loadInfos);
            this.getStats();
        },
        methods: {
            getStats() {
                let that = this;
                this.dataStatsLoading = true;
                axios.get(this.routeGetStats)
                    .then(function (response) {
                        that.dataStatsLoading = false;
                        that.isLoaded = true;
                        that.dataStats = response.data;
                        that.chartAdverts();
                        that.chartViews();
                        that.chartCosts();
                    })
                    .catch(function (error) {
                        that.dataCleanLoading = false;
                        that.isLoaded = true;
                        that.sendToast(that.strings.loadErrorMessage, 'error');
                    });
            },
            chartAdverts() {
                let that = this;
                AmCharts.makeChart("graphAdverts",
                    {
                        "type": "serial",
                        "categoryField": "date",
                        "dataDateFormat": "YYYY-MM-DD",
                        "categoryAxis": {
                            "parseDates": true
                        },
                        "chartCursor": {
                            "enabled": true
                        },
                        "chartScrollbar": {
                            "enabled": true
                        },
                        "trendLines": [],
                        "graphs": [
                            {
                                "bullet": "round",
                                "id": "AmGraph-1",
                                "lineColor": "#65BB65",
                                "title": that.strings.graphValidAdvertsLabel,
                                "valueField": "valid_adverts"
                            },
                            {
                                "bullet": "square",
                                "id": "AmGraph-2",
                                "lineColor": "#E57C7C",
                                "title": that.strings.graphInvalidAdvertsLabel,
                                "valueField": "invalid_adverts"
                            },
                            {
                                "bullet": "triangleUp",
                                "id": "AmGraph-3",
                                "lineColor": "#8A10BE",
                                "title": that.strings.graphWaitingAdvertsLabel,
                                "valueField": "waiting_adverts"
                            }
                        ],
                        "guides": [],
                        "valueAxes": [
                            {
                                "id": "ValueAxis-1",
                                "title": ""
                            }
                        ],
                        "allLabels": [],
                        "balloon": {},
                        "legend": {
                            "enabled": true,
                            "useGraphSettings": true
                        },
                        "titles": [
                            {
                                "id": "Title-1",
                                "size": 15,
                                "text": that.strings.graphAdvertTitle
                            }
                        ],
                        "dataProvider": that.dataStats.advertsByDay
                    });
            },
            chartCosts() {
                let that = this;
                AmCharts.makeChart("graphCosts", {
                    "theme": "light",
                    "type": "serial",
                    "categoryField": "date",
                    "dataDateFormat": "YYYY-MM-DD",
                    "categoryAxis": {
                        "parseDates": true,
                        "gridPosition": "start"
                    },
                    "dataProvider": that.dataStats.costsByDay,
                    "valueAxes": [{
                        "stackType": "3d",
                        "unit": "€",
                        "position": "left",
                        "title": "",
                    }],
                    "startDuration": 1,
                    "graphs": [{
                        "balloonText": that.strings.graphAvgCostsLabel + " <b>[[value]]</b>",
                        "fillAlphas": 0.9,
                        "lineAlpha": 0.2,
                        "title": "2005",
                        "type": "column",
                        "valueField": "avg_costs"
                    },
                    {
                        "balloonText": that.strings.graphSumCostsLabel + " <b>[[value]]</b>",
                        "fillAlphas": 0.9,
                        "lineAlpha": 0.2,
                        "title": "2004",
                        "type": "column",
                        "valueField": "sum_costs"
                    }],
                    "titles": [
                        {
                            "id": "Title-1",
                            "size": 15,
                            "text": that.strings.graphCostsTitle
                        }
                    ],
                    "plotAreaFillAlphas": 0.1,
                    "depth3D": 60,
                    "angle": 30,
                    "export": {
                        "enabled": true
                    }
                });
            },
            chartViews() {
                let that = this;
                AmCharts.makeChart("graphViews", {
                    "type": "serial",
                    "theme": "light",
                    "marginRight": 40,
                    "marginLeft": 40,
                    "autoMarginOffset": 20,
                    "mouseWheelZoomEnabled":true,
                    "dataDateFormat": "YYYY-MM-DD",
                    "valueAxes": [{
                        "id": "v1",
                        "axisAlpha": 0,
                        "position": "left",
                        "ignoreAxisWidth":true
                    }],
                    "balloon": {
                        "borderThickness": 1,
                        "shadowAlpha": 0
                    },
                    "graphs": [{
                        "id": "g1",
                        "balloon":{
                            "drop":true,
                            "adjustBorderColor":false,
                            "color":"#ffffff"
                        },
                        "bullet": "round",
                        "bulletBorderAlpha": 1,
                        "bulletColor": "#FFFFFF",
                        "bulletSize": 5,
                        "hideBulletsCount": 50,
                        "lineThickness": 2,
                        "title": "red line",
                        "useLineColorForBulletBorder": true,
                        "valueField": "views",
                        "balloonText": "<span style='font-size:18px;'>[[views]]</span>"
                    }],
                    "chartScrollbar": {
                        "graph": "g1",
                        "oppositeAxis":false,
                        "offset":30,
                        "scrollbarHeight": 80,
                        "backgroundAlpha": 0,
                        "selectedBackgroundAlpha": 0.1,
                        "selectedBackgroundColor": "#888888",
                        "graphFillAlpha": 0,
                        "graphLineAlpha": 0.5,
                        "selectedGraphFillAlpha": 0,
                        "selectedGraphLineAlpha": 1,
                        "autoGridCount":true,
                        "color":"#AAAAAA"
                    },
                    "chartCursor": {
                        "pan": true,
                        "valueLineEnabled": true,
                        "valueLineBalloonEnabled": true,
                        "cursorAlpha":1,
                        "cursorColor":"#258cbb",
                        "limitToGraph":"g1",
                        "valueLineAlpha":0.2,
                        "valueZoomable":true
                    },
                    "valueScrollbar":{
                        "oppositeAxis":false,
                        "offset":50,
                        "scrollbarHeight":10
                    },
                    "categoryField": "date",
                    "categoryAxis": {
                        "parseDates": true,
                        "dashLength": 1,
                        "minorGridEnabled": true
                    },
                    "export": {
                        "enabled": true
                    },
                    "dataProvider": that.dataStats.viewsByDay,
                    "titles": [
                        {
                            "id": "Title-1",
                            "size": 15,
                            "text": that.strings.graphViewsTitle
                        }
                    ]
                });
            },
            cleanApp() {
                let that = this;
                this.dataCleanLoading = true;
                axios.get(this.routeCleanApp)
                    .then(function (response) {
                        that.dataCleanLoading = false;
                        that.getStats();
                        that.sendToast(response.data, 'success');
                    })
                    .catch(function (error) {
                        that.dataCleanLoading = false;
                        error.response ? that.sendToast(error.response.data, 'error') : null;
                    });
            },
            sendToast: function (message, type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            },
            getFormatDate($date) {
                moment.locale(this.properties.actualLocale);
                return moment($date).format('L');
            }
        }
    }
</script>
