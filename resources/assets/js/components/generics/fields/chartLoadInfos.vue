<template>
    <div class="ui grid">
        <div class="sixteen wide column">
            <h3 class="ui header">{{ loadInfos.name }}</h3>
        </div>
        <div class="sixteen wide mobile twelve wide tablet twelve wide computer column">
            <div class="ui grid">
                <div class="sixteen wide column">
                    <div :id="'graph-1-'+_uid" style="width: 100%; height: 85px; background-color: #FFFFFF;"></div>
                </div>
                <div class="sixteen wide column">
                    <div :id="'graph-2-'+_uid" style="width: 100%; height: 85px; background-color: #FFFFFF;"></div>
                </div>
            </div>

        </div>
        <div class="sixteen wide mobile four wide tablet four wide computer column">
            <div class="ui center aligned grid">
                <div class="sixteen wide column">
                    <div class="ui statistic">
                        <div class="value">
                            {{ loadInfos.count }}
                        </div>
                        <div class="label">
                            {{ strings.countFilesTitle }}
                        </div>
                    </div>
                </div>
                <div class="sixteen wide column">
                    <div class="ui statistic">
                        <div class="value">
                            {{ loadInfos.size }}
                        </div>
                        <div class="label">
                            {{ strings.megaBytesLabel }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        props: {
            loadInfos: {
                type: Object
            }
        },
        data: () => {
            return {
                strings: {},
                properties: {},
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['chart-load-infos'];
            this.properties = this.$store.state.properties['global'];
            this.chartCountLoad();
            this.chartBytesLoad();
        },
        updated() {

        },
        methods: {
            chartCountLoad () {
                let that = this;
                AmCharts.makeChart("graph-1-" + that._uid,
                    {
                        "type": "serial",
                        "rotate": true,
                        "theme": "light",
                        "autoMargins": false,
                        "marginTop": 20,
                        "marginLeft": 100,
                        "marginBottom": 30,
                        "marginRight": 50,
                        "dataProvider": [{
                            "category": that.strings.countLoadLabel,
                            "excelent": 20,
                            "good": 20,
                            "average": 20,
                            "poor": 20,
                            "bad": 20,
                            "limit": 78,
                            "full": 100,
                            "bullet": that.loadInfos.count_load * 100
                        }],
                        "valueAxes": [{
                            "maximum": 100,
                            "stackType": "regular",
                            "gridAlpha": 0
                        }],
                        "startDuration": 1,
                        "graphs": [{
                            "valueField": "full",
                            "showBalloon": false,
                            "type": "column",
                            "lineAlpha": 0,
                            "fillAlphas": 0.8,
                            "fillColors": ["#19d228", "#f6d32b", "#fb2316"],
                            "gradientOrientation": "horizontal",
                        }, {
                            "clustered": false,
                            "columnWidth": 0.3,
                            "fillAlphas": 1,
                            "lineColor": "#000000",
                            "stackable": false,
                            "type": "column",
                            "valueField": "bullet"
                        }, {
                            "columnWidth": 0.5,
                            "lineColor": "#000000",
                            "lineThickness": 3,
                            "noStepRisers": true,
                            "stackable": false,
                            "type": "step",
                            "valueField": "limit"
                        }],
                        "columnWidth": 1,
                        "categoryField": "category",
                        "categoryAxis": {
                            "gridAlpha": 0,
                            "position": "left"
                        }
                    }
                );
            },
            chartBytesLoad () {
                let that = this;
                AmCharts.makeChart("graph-2-" + that._uid,
                    {
                        "type": "serial",
                        "rotate": true,
                        "theme": "light",
                        "autoMargins": false,
                        "marginTop": 20,
                        "marginLeft": 100,
                        "marginBottom": 30,
                        "marginRight": 50,
                        "dataProvider": [{
                            "category": that.strings.bytesLoadLabel,
                            "excelent": 20,
                            "good": 20,
                            "average": 20,
                            "poor": 20,
                            "bad": 20,
                            "limit": 78,
                            "full": 100,
                            "bullet": that.loadInfos.bytes_load * 100
                        }],
                        "valueAxes": [{
                            "maximum": 100,
                            "stackType": "regular",
                            "gridAlpha": 0
                        }],
                        "startDuration": 1,
                        "graphs": [{
                            "valueField": "full",
                            "showBalloon": false,
                            "type": "column",
                            "lineAlpha": 0,
                            "fillAlphas": 0.8,
                            "fillColors": ["#19d228", "#f6d32b", "#fb2316"],
                            "gradientOrientation": "horizontal",
                        }, {
                            "clustered": false,
                            "columnWidth": 0.3,
                            "fillAlphas": 1,
                            "lineColor": "#000000",
                            "stackable": false,
                            "type": "column",
                            "valueField": "bullet"
                        }, {
                            "columnWidth": 0.5,
                            "lineColor": "#000000",
                            "lineThickness": 3,
                            "noStepRisers": true,
                            "stackable": false,
                            "type": "step",
                            "valueField": "limit"
                        }],
                        "columnWidth": 1,
                        "categoryField": "category",
                        "categoryAxis": {
                            "gridAlpha": 0,
                            "position": "left"
                        }
                    }
                );
            }
        }
    }
</script>
