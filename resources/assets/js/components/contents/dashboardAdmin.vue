<template>
    <div class="ui one column grid">
        <toast :send-message="sendMessage" :message="message" :type="typeMessage"></toast>
        <div class="column">
            <h2 class="ui header">{{ contentHeader }}</h2>
        </div>
        <div class="column">
            <div class="ui active inverted dimmer" v-if="!isLoaded">
                <div class="ui large text loader">Loading</div>
            </div>
            <div class="ui form">
                <h4 class="ui horizontal divider header"><i class="dashboard icon"></i> {{ dashboardLabel }} </h4>
                <div class="ui grid">
                    <div class="sixteen wide mobile six wide computer column">
                        <div id="graphSizeLocal" style="width: 100%; height: 280px; background-color: #FFFFFF;"></div>
                    </div>
                    <div class="four wide computer only column">
                        <div id="chartFilesBalance" style="width: 100%; height: 280px; background-color: #FFFFFF;"></div>
                    </div>
                    <div class="sixteen wide mobile six wide computer column">
                        <div id="graphSizeDistant" style="width: 100%; height: 280px; background-color: #FFFFFF;"></div>
                    </div>
                </div>
                <div class="ui grid">
                    <div class="sixteen wide column" v-show="dataTransfertMediasLoading">
                        <div :id="'progress-'+_uid" :class="dataTransfertMediasProgress != 100 ? 'ui active indicating progress' : 'ui progress success'" :data-value="dataTransfertMediasProgress" data-total="100">
                            <div class="bar">
                                <div class="progress"></div>
                            </div>
                            <div class="label">Transfert Files</div>
                        </div>
                    </div>
                    <div class="sixteen wide mobile eight wide computer column">
                        <button :class="dataCleanLoading ? 'ui primary loading button' : 'ui primary button' "
                                v-on:click="cleanApp">{{ menuCleanAppLabel }}</button>
                    </div>
                    <div class="eight wide mobile four wide computer column">
                        <div class="ui fluid right labeled input">
                            <input type="number" min="0" :max="readSizeLocalFiles()" step="1" v-model="sizeToTransfert">
                            <div class="ui label">
                                {{ megaBytesLabel }}
                            </div>
                        </div>
                    </div>
                    <div class="eight wide mobile four wide computer column">
                        <button :class="dataTransfertMediasLoading ? 'ui primary loading button' : (parseInt(sizeToTransfert) > 0 ? 'ui primary button' : 'ui disabled button')"
                                v-on:click="transfertMedias">{{ menuTransfertMediasLabel }}</button>
                    </div>
                </div>
                <h4 class="ui horizontal divider header"><i class="line chart icon"></i> {{ statsLabel }} </h4>
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
            routeTransfertMedias: String,
            routeProgressTransfertMedias: String,
            routeGetStats: String,
            //vue vars
            //vue strings
            contentHeader: String,
            loadErrorMessage: String,
            dashboardLabel: String,
            menuCleanAppLabel: String,
            menuTransfertMediasLabel: String,
            sizeLocalFileLabel: String,
            sizeDistantFileLabel: String,
            countFilesTitle: String,
            countLocalFilesLabel: String,
            countDistantFilesLabel: String,
            megaBytesLabel: String,
            statsLabel: String,
            graphAdvertTitle: String,
            graphValidAdvertsLabel: String,
            graphInvalidAdvertsLabel: String,
            graphWaitingAdvertsLabel: String,
            graphCostsTitle: String,
            graphSumCostsLabel: String,
            graphAvgCostsLabel: String,
            graphViewsTitle: String
        },
        data: () => {
            return {
                isLoaded: false,
                sendMessage: false,
                typeMessage: '',
                message: '',
                dataCleanLoading: false,
                dataTransfertMediasLoading: false,
                dataTransfertMediasProgress: 0,
                dataStatsLoading : false,
                dataStats: {},
                sizeToTransfert: 0
            };
        },
        mounted () {
            let that = this;
            this.getStats();
            $('#progress-'+this._uid).progress();
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
                        that.chartLocalFileSize();
                        that.chartDistantFileSize();
                        that.chartFilesBalance();
                        that.chartAdverts();
                        that.chartViews();
                        that.chartCosts();
                        that.sizeToTransfert = 0;
                    })
                    .catch(function (error) {
                        that.dataCleanLoading = false;
                        that.isLoaded = true;
                        that.sendToast(that.loadErrorMessage, 'error');
                    });
            },
            chartLocalFileSize () {
                let that = this;
                let filesInfo = this.dataStats.filesInfo;
                AmCharts.makeChart("graphSizeLocal",
                    {
                        "type": "gauge",
                        "marginBottom": 10,
                        "marginTop": 20,
                        "startDuration": 1.2,
                        "fontSize": 8,
                        "theme": "default",
                        "arrows": [
                            {
                                "id": "GaugeArrow-1",
                                //"value": 120
                                "value": filesInfo.sizeLocalFiles
                            }
                        ],
                        "axes": [
                            {
                                "bottomText": filesInfo.sizeLocalFiles + " " + that.megaBytesLabel,
                                "bottomTextYOffset": -20,
                                "endValue": 220,
                                "id": "GaugeAxis-1",
                                "topText": that.sizeLocalFileLabel,
                                "valueInterval": 20,
                                "bands": [
                                    {
                                        "alpha": 0.7,
                                        "color": "#00CC00",
                                        "endValue": 90,
                                        "id": "GaugeBand-1",
                                        "startValue": 0
                                    },
                                    {
                                        "alpha": 0.7,
                                        "color": "#ffac29",
                                        "endValue": 130,
                                        "id": "GaugeBand-2",
                                        "startValue": 90
                                    },
                                    {
                                        "alpha": 0.7,
                                        "color": "#ea3838",
                                        "endValue": 220,
                                        "id": "GaugeBand-3",
                                        "innerRadius": "95%",
                                        "startValue": 130
                                    }
                                ]
                            }
                        ],
                        "allLabels": [],
                        "balloon": {},
                        "titles": []
                    }
                );
            },
            chartDistantFileSize () {
                let that = this;
                let filesInfo = this.dataStats.filesInfo;
                AmCharts.makeChart("graphSizeDistant",
                    {
                        "type": "gauge",
                        "marginBottom": 10,
                        "marginTop": 20,
                        "startDuration": 1.2,
                        "fontSize": 8,
                        "theme": "default",
                        "arrows": [
                            {
                                "id": "GaugeArrow-1",
                                //"value": 120
                                "value": filesInfo.sizeDistantFiles
                            }
                        ],
                        "axes": [
                            {
                                "bottomText": filesInfo.sizeDistantFiles + " " + that.megaBytesLabel,
                                "bottomTextYOffset": -20,
                                "endValue": 220,
                                "id": "GaugeAxis-1",
                                "topText": that.sizeDistantFileLabel,
                                "valueInterval": 20,
                                "bands": [
                                    {
                                        "alpha": 0.7,
                                        "color": "#00CC00",
                                        "endValue": 90,
                                        "id": "GaugeBand-1",
                                        "startValue": 0
                                    },
                                    {
                                        "alpha": 0.7,
                                        "color": "#ffac29",
                                        "endValue": 130,
                                        "id": "GaugeBand-2",
                                        "startValue": 90
                                    },
                                    {
                                        "alpha": 0.7,
                                        "color": "#ea3838",
                                        "endValue": 220,
                                        "id": "GaugeBand-3",
                                        "innerRadius": "95%",
                                        "startValue": 130
                                    }
                                ]
                            }
                        ],
                        "allLabels": [],
                        "balloon": {},
                        "titles": []
                    }
                );
            },
            chartFilesBalance() {
                let that = this;
                let filesInfo = this.dataStats.filesInfo;
                AmCharts.makeChart("chartFilesBalance",
                    {
                        "type": "serial",
                        "theme": "light",
                        "addClassNames": "true",
                        "dataProvider": [{
                            "name": that.countLocalFilesLabel,
                            "points": filesInfo.countLocalFiles,
                            "color": "#7F8DA9",
                            "bullet": "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwMCAyMDAiIGhlaWdodD0iMjAwcHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyMDAgMjAwIiB3aWR0aD0iMjAwcHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxnPjxwYXRoIGQ9Ik0xNzguODM2LDBIMjEuMTYyYy01LjExNiwwLTkuMzAxLDQuMTg1LTkuMzAxLDkuMzAzdjI3LjkwNmMwLDUuMTE2LDQuMTg1LDkuMzAzLDkuMzAxLDkuMzAzaDE1Ny42NzQgICAgYzUuMTE2LDAsOS4zMDQtNC4xODcsOS4zMDQtOS4zMDNWOS4zMDNDMTg4LjE0LDQuMTg1LDE4My45NTIsMCwxNzguODM2LDB6IE0xMzMuMjk4LDI1LjUyNmgtMjMuODMgICAgYy0xLjI0NSwwLTIuMjctMS4wMjMtMi4yNy0yLjI2OGMwLTEuMjUsMS4wMjQtMi4yNzIsMi4yNy0yLjI3MmgyMy44M2MxLjI0NywwLDIuMjcxLDEuMDIzLDIuMjcxLDIuMjcyICAgIEMxMzUuNTY4LDI0LjUwMywxMzQuNTQ1LDI1LjUyNiwxMzMuMjk4LDI1LjUyNnogTTE0Ny42NzQsMjguMzYxYy0yLjgyMiwwLTUuMTA5LTIuMjg3LTUuMTA5LTUuMTAzICAgIGMwLTIuODI0LDIuMjg3LTUuMTA5LDUuMTA5LTUuMTA5YzIuODE4LDAsNS4xMDQsMi4yODUsNS4xMDQsNS4xMDlDMTUyLjc3NywyNi4wNzQsMTUwLjQ5MiwyOC4zNjEsMTQ3LjY3NCwyOC4zNjF6ICAgICBNMTY0Ljg4MiwyOC4zNjFjLTIuODIsMC01LjEwNy0yLjI4Ny01LjEwNy01LjEwM2MwLTIuODI0LDIuMjg3LTUuMTA5LDUuMTA3LTUuMTA5YzIuODIyLDAsNS4xMDcsMi4yODUsNS4xMDcsNS4xMDkgICAgQzE2OS45ODksMjYuMDc0LDE2Ny43MDQsMjguMzYxLDE2NC44ODIsMjguMzYxeiIgZmlsbD0iIzVFODg5RSIvPjxwYXRoIGQ9Ik0xNzguODM2LDU1LjgxNUgyMS4xNjJjLTUuMTE2LDAtOS4zMDEsNC4xODYtOS4zMDEsOS4yOTl2MjcuOTFjMCw1LjExNiw0LjE4NSw5LjI5OSw5LjMwMSw5LjI5OWgxNTcuNjc0ICAgIGM1LjExNiwwLDkuMzA0LTQuMTgzLDkuMzA0LTkuMjk5di0yNy45MUMxODguMTQsNjAuMDAxLDE4My45NTIsNTUuODE1LDE3OC44MzYsNTUuODE1eiBNMTMzLjI5OCw4MS4zMzhoLTIzLjgzICAgIGMtMS4yNDUsMC0yLjI3LTEuMDE5LTIuMjctMi4yNjhjMC0xLjI0OCwxLjAyNC0yLjI2OCwyLjI3LTIuMjY4aDIzLjgzYzEuMjQ3LDAsMi4yNzEsMS4wMjEsMi4yNzEsMi4yNjggICAgQzEzNS41NjgsODAuMzE5LDEzNC41NDUsODEuMzM4LDEzMy4yOTgsODEuMzM4eiBNMTQ3LjY3NCw4NC4xNzVjLTIuODIyLDAtNS4xMDktMi4yODUtNS4xMDktNS4xMDUgICAgYzAtMi44MiwyLjI4Ny01LjEwNSw1LjEwOS01LjEwNWMyLjgxOCwwLDUuMTA0LDIuMjg1LDUuMTA0LDUuMTA1QzE1Mi43NzcsODEuODksMTUwLjQ5Miw4NC4xNzUsMTQ3LjY3NCw4NC4xNzV6IE0xNjQuODgyLDg0LjE3NSAgICBjLTIuODIsMC01LjEwNy0yLjI4NS01LjEwNy01LjEwNWMwLTIuODIsMi4yODctNS4xMDUsNS4xMDctNS4xMDVjMi44MjIsMCw1LjEwNywyLjI4NSw1LjEwNyw1LjEwNSAgICBDMTY5Ljk4OSw4MS44OSwxNjcuNzA0LDg0LjE3NSwxNjQuODgyLDg0LjE3NXoiIGZpbGw9IiM1RTg4OUUiLz48cGF0aCBkPSJNMTc4LjgzNiwxMTEuNjI3SDIxLjE2MmMtNS4xMTYsMC05LjMwMSw0LjE4OS05LjMwMSw5LjMwNHYyNy45MDljMCw1LjExNCw0LjE4NSw5LjMsOS4zMDEsOS4zaDE1Ny42NzQgICAgYzUuMTE2LDAsOS4zMDQtNC4xODYsOS4zMDQtOS4zdi0yNy45MDlDMTg4LjE0LDExNS44MTYsMTgzLjk1MiwxMTEuNjI3LDE3OC44MzYsMTExLjYyN3ogTTEzMy4yOTgsMTM3LjE1M2gtMjMuODMgICAgYy0xLjI0NSwwLTIuMjctMS4wMjEtMi4yNy0yLjI2OGMwLTEuMjQ4LDEuMDI0LTIuMjcyLDIuMjctMi4yNzJoMjMuODNjMS4yNDcsMCwyLjI3MSwxLjAyNCwyLjI3MSwyLjI3MiAgICBDMTM1LjU2OCwxMzYuMTMzLDEzNC41NDUsMTM3LjE1MywxMzMuMjk4LDEzNy4xNTN6IE0xNDcuNjc0LDEzOS45OWMtMi44MjIsMC01LjEwOS0yLjI4NS01LjEwOS01LjEwNCAgICBjMC0yLjgyLDIuMjg3LTUuMTA3LDUuMTA5LTUuMTA3YzIuODE4LDAsNS4xMDQsMi4yODcsNS4xMDQsNS4xMDdDMTUyLjc3NywxMzcuNzA1LDE1MC40OTIsMTM5Ljk5LDE0Ny42NzQsMTM5Ljk5eiAgICAgTTE2NC44ODIsMTM5Ljk5Yy0yLjgyLDAtNS4xMDctMi4yODUtNS4xMDctNS4xMDRjMC0yLjgyLDIuMjg3LTUuMTA3LDUuMTA3LTUuMTA3YzIuODIyLDAsNS4xMDcsMi4yODcsNS4xMDcsNS4xMDcgICAgQzE2OS45ODksMTM3LjcwNSwxNjcuNzA0LDEzOS45OSwxNjQuODgyLDEzOS45OXoiIGZpbGw9IiM1RTg4OUUiLz48L2c+PGc+PHBhdGggZD0iTTc5LjA3LDE5MC4yMzJjMCwxLjUzNi0xLjI1NiwyLjc5Mi0yLjc5MiwyLjc5MmgtNDAuOTNjLTEuNTM0LDAtMi43OTEtMS4yNTYtMi43OTEtMi43OTJ2LTMuNzIxICAgIGMwLTEuNTMyLDEuMjU2LTIuNzksMi43OTEtMi43OWg0MC45M2MxLjUzNiwwLDIuNzkyLDEuMjU4LDIuNzkyLDIuNzlWMTkwLjIzMnoiIGZpbGw9IiM1RTg4OUUiLz48cGF0aCBkPSJNMTY3LjQ0MiwxOTAuMjMyYzAsMS41MzYtMS4yNTgsMi43OTItMi43OTIsMi43OTJoLTQwLjkzYy0xLjUzNiwwLTIuNzktMS4yNTYtMi43OS0yLjc5MnYtMy43MjEgICAgYzAtMS41MzIsMS4yNTQtMi43OSwyLjc5LTIuNzloNDAuOTNjMS41MzQsMCwyLjc5MiwxLjI1OCwyLjc5MiwyLjc5VjE5MC4yMzJ6IiBmaWxsPSIjNUU4ODlFIi8+PHBhdGggZD0iTTExMC44MTIsMTc3LjU2M2MtMC41NDgtMC41NDgtMS4yNjItMC44MTktMS45NzctMC44MTloLTQuMTg2di02LjUxMmMwLTAuNzE0LTAuMjctMS40MjUtMC44MTUtMS45NzMgICAgYy0wLjU0My0wLjU0NS0xLjI2LTAuODE3LTEuOTczLTAuODE3aC0zLjcyMWMtMC43MTUsMC0xLjQyOSwwLjI3Mi0xLjk3NSwwLjgxN2MtMC41NDUsMC41NDgtMC44MTksMS4yNTktMC44MTksMS45NzN2Ni41MTIgICAgaC00LjE4NWMtMC43MSwwLTEuNDI1LDAuMjcxLTEuOTczLDAuODE5Yy0wLjU0MywwLjU0My0wLjgxOSwxLjI1OC0wLjgxOSwxLjk3M3YxNy42NzRjMCwwLjcxNSwwLjI3NiwxLjQyOSwwLjgxOSwxLjk3NSAgICBjMC41NDgsMC41NDQsMS4yNjMsMC44MTUsMS45NzMsMC44MTVoMTcuNjc0YzAuNzE1LDAsMS40MjktMC4yNzEsMS45NzctMC44MTVjMC41NDQtMC41NDYsMC44MTctMS4yNiwwLjgxNy0xLjk3NXYtMTcuNjc0ICAgIEMxMTEuNjI5LDE3OC44MjEsMTExLjM1NSwxNzguMTA2LDExMC44MTIsMTc3LjU2M3oiIGZpbGw9IiM1RTg4OUUiLz48L2c+PC9nPjwvc3ZnPg==",
                        }, {
                            "name": that.countDistantFilesLabel,
                            "points": filesInfo.countDistantFiles,
                            "color": "#FEC514",
                            "bullet": "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwMCAyMDAiIGhlaWdodD0iMjAwcHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyMDAgMjAwIiB3aWR0aD0iMjAwcHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxwYXRoIGQ9Ik0xMTcuNDc0LDUyLjQyNGg3LjkzMXY2LjE3M2MwLjYwOS0wLjAzOSwxLjIyMy0wLjA2MywxLjgzOS0wLjA2M2MxNS42MDksMCwyOC40ODMsMTEuOTQ1LDI5Ljk1LDI3LjE3OSAgIGMwLjc0OC0wLjAzOSwxLjQ5Mi0wLjA1NywyLjIzMi0wLjA1N2MyLjQ3OCwwLDQuOTA4LDAuMjA2LDcuMjgyLDAuNTgxVjUyLjQyNGg3LjkzMWMxLjMzMiwwLDIuNTk4LTAuNzksMy4xMzYtMi4wOTQgICBjMC41NDItMS4zMDgsMC4yMDYtMi43NTctMC43MzctMy43bC0yOC41NzgtMjguNTgzYy0wLjYxMy0wLjYxMy0xLjQ2OC0wLjk5Ni0yLjQwMy0wLjk5NnMtMS43ODYsMC4zODMtMi40MDIsMC45OTZMMTE1LjA3MSw0Ni42MyAgIGMtMC45MzksMC45NDMtMS4yNzYsMi4zOTItMC43MzQsMy43QzExNC44OCw1MS42MzQsMTE2LjE0MSw1Mi40MjQsMTE3LjQ3NCw1Mi40MjR6IiBmaWxsPSIjNUU4ODlFIi8+PHBhdGggZD0iTTE1OS40MjYsOTEuMDgxYy0yLjY0NCwwLTUuMjI3LDAuMjYyLTcuNzI5LDAuNzQ1YzAuMTM1LTEuMDQ5LDAuMjEzLTIuMTE2LDAuMjEzLTMuMiAgIGMwLTEzLjYyMi0xMS4wNDYtMjQuNjY3LTI0LjY2Ny0yNC42NjdjLTQuOTg2LDAtOS42MTcsMS40ODEtMTMuNDk0LDQuMDIyYy02LjMxOC0xNy42NjgtMjMuMi0zMC4zMTItNDMuMDQxLTMwLjMxMiAgIGMtMjUuMjM3LDAtNDUuNzAyLDIwLjQ2LTQ1LjcwMiw0NS43MDJjMCwxLjgsMC4xMTcsMy41NzUsMC4zMjMsNS4zMjZjLTguNTE5LDAuNzU1LTE1LjIwMiw3LjkwMi0xNS4yMDIsMTYuNjIgICBjMCwzLjkwNSwxLjM0Myw3LjQ4NywzLjU5LDEwLjMzM0M1LjQ0NiwxMjEuMTk0LDAsMTMwLjYyNCwwLDE0MS4zMjljMCwxNy4wNjUsMTMuODM0LDMwLjg5OSwzMC45LDMwLjg5OWgzNC40NzlsLTE1LjAyNS0xNS4wMjQgICBjLTIuNTMtMi41MjYtMy4yODEtNi4zMDEtMS45MS05LjYxYzEuMzY4LTMuMzA2LDQuNTY4LTUuNDQyLDguMTQ2LTUuNDQyaDIuNTA1di0zMi40ODhjMC0yLjM1MywwLjkxOC00LjU2NywyLjU4My02LjIzMiAgIGMxLjY2OS0xLjY3LDMuODgtMi41ODcsNi4yMzctMi41ODdoMzQuNTE0YzIuMzU2LDAsNC41NzEsMC45MTcsNi4yMzcsMi41ODdjMS42NjUsMS42NjEsMi41ODMsMy44OCwyLjU4Myw2LjIzMnYzMi40ODhoMi41MDUgICBjMy41NzksMCw2Ljc3OSwyLjEzNyw4LjE0Nyw1LjQ0NmMxLjM3MSwzLjMwMywwLjYyLDcuMDc2LTEuOTEsOS42MDZsLTE1LjAyNSwxNS4wMjRoNTQuNDU4YzIyLjQwNiwwLDQwLjU3NC0xOC4xNjQsNDAuNTc0LTQwLjU3MyAgIEMyMDAsMTA5LjI0NSwxODEuODMyLDkxLjA4MSwxNTkuNDI2LDkxLjA4MXoiIGZpbGw9IiM1RTg4OUUiLz48cGF0aCBkPSJNMTE2Ljg5MywxNDkuNjcxYy0wLjUzOS0xLjMwNS0xLjgwNC0yLjA5NS0zLjEzNy0yLjA5NWgtNy45Mjd2LTM3LjkxM2MwLTAuODY4LTAuMzM3LTEuNzM2LTAuOTk5LTIuMzk4ICAgYy0wLjY1OS0wLjY2My0xLjUyNy0wLjk5Ni0yLjM5OS0wLjk5Nkg2Ny45MTdjLTAuODY4LDAtMS43MzYsMC4zMzMtMi4zOTksMC45OTZjLTAuNjY3LDAuNjYyLTAuOTk2LDEuNTMtMC45OTYsMi4zOTh2MzcuOTEzICAgaC03LjkzMWMtMS4zMzIsMC0yLjU5NCwwLjc5LTMuMTM2LDIuMDk1Yy0wLjUzOSwxLjMwOC0wLjIwNiwyLjc1NywwLjczNywzLjY5OWwyOC41ODMsMjguNTgyYzAuNjEzLDAuNjEzLDEuNDY0LDAuOTk2LDIuMzk5LDAuOTk2ICAgYzAuOTM5LDAsMS43ODYtMC4zODMsMi40MDItMC45OTZsMjguNTgzLTI4LjU4MkMxMTcuMTAyLDE1Mi40MjgsMTE3LjQzNSwxNTAuOTc5LDExNi44OTMsMTQ5LjY3MXoiIGZpbGw9IiM1RTg4OUUiLz48L2c+PC9zdmc+"
                        }],
                        "valueAxes": [{
                            "maximum": Math.max([filesInfo.countLocalFiles, filesInfo.countDistantFiles])*1.2,
                            "minimum": 0,
                            "axisAlpha": 0,
                            "dashLength": 4,
                            "position": "left"
                        }],
                        "startDuration": 1,
                        "graphs": [{
                            "balloonText": "<span style='font-size:13px;'>[[category]]: <b>[[value]]</b></span>",
                            "bulletOffset": 10,
                            "bulletSize": 52,
                            "colorField": "color",
                            "cornerRadiusTop": 8,
                            "customBulletField": "bullet",
                            "fillAlphas": 0.8,
                            "lineAlpha": 0,
                            "type": "column",
                            "valueField": "points"

                        }],
                        "marginTop": 10,
                        "marginRight": 0,
                        "marginLeft": 0,
                        "marginBottom": 40,
                        "autoMargins": false,
                        "categoryField": "name",
                        "categoryAxis": {
                            "axisAlpha": 0,
                            "gridAlpha": 0,
                            "inside": true,
                            "tickLength": 0
                        },
                        "titles": [
                            {
                                "id": "Title-1",
                                "size": 15,
                                "text": that.countFilesTitle
                            }
                        ],
                        "export": {
                            "enabled": false
                        }
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
                                "title": that.graphValidAdvertsLabel,
                                "valueField": "valid_adverts"
                            },
                            {
                                "bullet": "square",
                                "id": "AmGraph-2",
                                "lineColor": "#E57C7C",
                                "title": that.graphInvalidAdvertsLabel,
                                "valueField": "invalid_adverts"
                            },
                            {
                                "bullet": "triangleUp",
                                "id": "AmGraph-3",
                                "lineColor": "#8A10BE",
                                "title": that.graphWaitingAdvertsLabel,
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
                                "text": that.graphAdvertTitle
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
                        "balloonText": that.graphAvgCostsLabel + " <b>[[value]]</b>",
                        "fillAlphas": 0.9,
                        "lineAlpha": 0.2,
                        "title": "2005",
                        "type": "column",
                        "valueField": "avg_costs"
                    },
                    {
                        "balloonText": that.graphSumCostsLabel + " <b>[[value]]</b>",
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
                            "text": that.graphCostsTitle
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
                            "text": that.graphViewsTitle
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
            transfertMedias() {
                let that = this;
                this.dataTransfertMediasLoading = true;
                axios.get(this.routeTransfertMedias+'/'+this.sizeToTransfert)
                    .then(function (response) {
                        that.getProgressTransfertMedias();
                        that.sendToast(response.data, 'success');
                    })
                    .catch(function (error) {
                        that.dataTransfertMediasLoading = false;
                        error.response ? that.sendToast(error.response.data, 'error') : null;
                    });
            },
            getProgressTransfertMedias () {
                let that = this;
                let myTimeout = setTimeout(function () {
                    axios.get(that.routeProgressTransfertMedias)
                        .then(function (response) {
                            let progress = response.data;
                            if(progress[2] > 0){
                                let percent = progress[1]/progress[2]*100;
                                if(percent<100){
                                    that.dataTransfertMediasProgress= progress[1]/progress[2]*100;
                                } else {
                                    that.dataTransfertMediasProgress= 100;
                                }
                            } else {
                                that.dataTransfertMediasProgress = 0;
                            }
                            if(progress[0] == 0 && progress[2] !=0) {
                                that.dataTransfertMediasLoading = false;
                                that.dataTransfertMediasProgress = 0;
                                $('#progress-'+that._uid).progress({
                                    percent: that.dataTransfertMediasProgress
                                });
                                that.getStats();
                            } else {
                                that.getProgressTransfertMedias();
                                $('#progress-'+that._uid).progress({
                                    percent: that.dataTransfertMediasProgress
                                });
                            }
                        })
                        .catch(function (error) {
                            that.dataCleanLoading = false;
                            error.response ? that.sendToast(error.response.data, 'error') : null;
                        });
                }, 1000);
            },
            readSizeLocalFiles() {
                if('filesInfo' in this.dataStats){
                    if('sizeLocalFiles' in this.dataStats.filesInfo){
                        return this.dataStats.filesInfo.sizeLocalFiles;
                    }
                }
                return 0;
            },
            sendToast: function (message, type) {
                this.typeMessage = type;
                this.message = message;
                this.sendMessage = !this.sendMessage;
            }
        }
    }
</script>
