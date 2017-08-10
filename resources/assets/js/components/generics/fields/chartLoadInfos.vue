<template>
    <div class="ui grid">
        <div class="sixteen wide column">
            <h3 class="ui header">{{ loadInfos.name }}</h3>
        </div>
        <div class="sixteen wide mobile six wide computer column">
            <div :id="'graph-1-'+_uid" style="width: 100%; height: 280px; background-color: #FFFFFF;"></div>
        </div>
        <div class="four wide computer only column">
            <div :id="'graph-2-'+_uid" style="width: 100%; height: 280px; background-color: #FFFFFF;"></div>
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
            this.chartFileSize();
            this.chartFilesCount();
        },
        updated() {

        },
        methods: {
            chartFileSize () {
                let that = this;
                AmCharts.makeChart("graph-1-"+that._uid,
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
                                "value": that.loadInfos.size
                            }
                        ],
                        "axes": [
                            {
                                "bottomText": that.loadInfos.size + " " + that.strings.megaBytesLabel,
                                "bottomTextYOffset": -20,
                                "endValue": 220,
                                "id": "GaugeAxis-1",
                                "topText": that.loadInfos.name,
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
            chartFilesCount() {
                let that = this;
                AmCharts.makeChart("graph-2-"+that._uid,
                    {
                        "type": "serial",
                        "theme": "light",
                        "addClassNames": "true",
                        "dataProvider": [{
                            "name": that.loadInfos.name,
                            "points": that.loadInfos.count,
                            "color": "#7F8DA9",
                            "bullet": "data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiA/PjwhRE9DVFlQRSBzdmcgIFBVQkxJQyAnLS8vVzNDLy9EVEQgU1ZHIDEuMS8vRU4nICAnaHR0cDovL3d3dy53My5vcmcvR3JhcGhpY3MvU1ZHLzEuMS9EVEQvc3ZnMTEuZHRkJz48c3ZnIGVuYWJsZS1iYWNrZ3JvdW5kPSJuZXcgMCAwIDIwMCAyMDAiIGhlaWdodD0iMjAwcHgiIGlkPSJMYXllcl8xIiB2ZXJzaW9uPSIxLjEiIHZpZXdCb3g9IjAgMCAyMDAgMjAwIiB3aWR0aD0iMjAwcHgiIHhtbDpzcGFjZT0icHJlc2VydmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyIgeG1sbnM6eGxpbms9Imh0dHA6Ly93d3cudzMub3JnLzE5OTkveGxpbmsiPjxnPjxnPjxwYXRoIGQ9Ik0xNzguODM2LDBIMjEuMTYyYy01LjExNiwwLTkuMzAxLDQuMTg1LTkuMzAxLDkuMzAzdjI3LjkwNmMwLDUuMTE2LDQuMTg1LDkuMzAzLDkuMzAxLDkuMzAzaDE1Ny42NzQgICAgYzUuMTE2LDAsOS4zMDQtNC4xODcsOS4zMDQtOS4zMDNWOS4zMDNDMTg4LjE0LDQuMTg1LDE4My45NTIsMCwxNzguODM2LDB6IE0xMzMuMjk4LDI1LjUyNmgtMjMuODMgICAgYy0xLjI0NSwwLTIuMjctMS4wMjMtMi4yNy0yLjI2OGMwLTEuMjUsMS4wMjQtMi4yNzIsMi4yNy0yLjI3MmgyMy44M2MxLjI0NywwLDIuMjcxLDEuMDIzLDIuMjcxLDIuMjcyICAgIEMxMzUuNTY4LDI0LjUwMywxMzQuNTQ1LDI1LjUyNiwxMzMuMjk4LDI1LjUyNnogTTE0Ny42NzQsMjguMzYxYy0yLjgyMiwwLTUuMTA5LTIuMjg3LTUuMTA5LTUuMTAzICAgIGMwLTIuODI0LDIuMjg3LTUuMTA5LDUuMTA5LTUuMTA5YzIuODE4LDAsNS4xMDQsMi4yODUsNS4xMDQsNS4xMDlDMTUyLjc3NywyNi4wNzQsMTUwLjQ5MiwyOC4zNjEsMTQ3LjY3NCwyOC4zNjF6ICAgICBNMTY0Ljg4MiwyOC4zNjFjLTIuODIsMC01LjEwNy0yLjI4Ny01LjEwNy01LjEwM2MwLTIuODI0LDIuMjg3LTUuMTA5LDUuMTA3LTUuMTA5YzIuODIyLDAsNS4xMDcsMi4yODUsNS4xMDcsNS4xMDkgICAgQzE2OS45ODksMjYuMDc0LDE2Ny43MDQsMjguMzYxLDE2NC44ODIsMjguMzYxeiIgZmlsbD0iIzVFODg5RSIvPjxwYXRoIGQ9Ik0xNzguODM2LDU1LjgxNUgyMS4xNjJjLTUuMTE2LDAtOS4zMDEsNC4xODYtOS4zMDEsOS4yOTl2MjcuOTFjMCw1LjExNiw0LjE4NSw5LjI5OSw5LjMwMSw5LjI5OWgxNTcuNjc0ICAgIGM1LjExNiwwLDkuMzA0LTQuMTgzLDkuMzA0LTkuMjk5di0yNy45MUMxODguMTQsNjAuMDAxLDE4My45NTIsNTUuODE1LDE3OC44MzYsNTUuODE1eiBNMTMzLjI5OCw4MS4zMzhoLTIzLjgzICAgIGMtMS4yNDUsMC0yLjI3LTEuMDE5LTIuMjctMi4yNjhjMC0xLjI0OCwxLjAyNC0yLjI2OCwyLjI3LTIuMjY4aDIzLjgzYzEuMjQ3LDAsMi4yNzEsMS4wMjEsMi4yNzEsMi4yNjggICAgQzEzNS41NjgsODAuMzE5LDEzNC41NDUsODEuMzM4LDEzMy4yOTgsODEuMzM4eiBNMTQ3LjY3NCw4NC4xNzVjLTIuODIyLDAtNS4xMDktMi4yODUtNS4xMDktNS4xMDUgICAgYzAtMi44MiwyLjI4Ny01LjEwNSw1LjEwOS01LjEwNWMyLjgxOCwwLDUuMTA0LDIuMjg1LDUuMTA0LDUuMTA1QzE1Mi43NzcsODEuODksMTUwLjQ5Miw4NC4xNzUsMTQ3LjY3NCw4NC4xNzV6IE0xNjQuODgyLDg0LjE3NSAgICBjLTIuODIsMC01LjEwNy0yLjI4NS01LjEwNy01LjEwNWMwLTIuODIsMi4yODctNS4xMDUsNS4xMDctNS4xMDVjMi44MjIsMCw1LjEwNywyLjI4NSw1LjEwNyw1LjEwNSAgICBDMTY5Ljk4OSw4MS44OSwxNjcuNzA0LDg0LjE3NSwxNjQuODgyLDg0LjE3NXoiIGZpbGw9IiM1RTg4OUUiLz48cGF0aCBkPSJNMTc4LjgzNiwxMTEuNjI3SDIxLjE2MmMtNS4xMTYsMC05LjMwMSw0LjE4OS05LjMwMSw5LjMwNHYyNy45MDljMCw1LjExNCw0LjE4NSw5LjMsOS4zMDEsOS4zaDE1Ny42NzQgICAgYzUuMTE2LDAsOS4zMDQtNC4xODYsOS4zMDQtOS4zdi0yNy45MDlDMTg4LjE0LDExNS44MTYsMTgzLjk1MiwxMTEuNjI3LDE3OC44MzYsMTExLjYyN3ogTTEzMy4yOTgsMTM3LjE1M2gtMjMuODMgICAgYy0xLjI0NSwwLTIuMjctMS4wMjEtMi4yNy0yLjI2OGMwLTEuMjQ4LDEuMDI0LTIuMjcyLDIuMjctMi4yNzJoMjMuODNjMS4yNDcsMCwyLjI3MSwxLjAyNCwyLjI3MSwyLjI3MiAgICBDMTM1LjU2OCwxMzYuMTMzLDEzNC41NDUsMTM3LjE1MywxMzMuMjk4LDEzNy4xNTN6IE0xNDcuNjc0LDEzOS45OWMtMi44MjIsMC01LjEwOS0yLjI4NS01LjEwOS01LjEwNCAgICBjMC0yLjgyLDIuMjg3LTUuMTA3LDUuMTA5LTUuMTA3YzIuODE4LDAsNS4xMDQsMi4yODcsNS4xMDQsNS4xMDdDMTUyLjc3NywxMzcuNzA1LDE1MC40OTIsMTM5Ljk5LDE0Ny42NzQsMTM5Ljk5eiAgICAgTTE2NC44ODIsMTM5Ljk5Yy0yLjgyLDAtNS4xMDctMi4yODUtNS4xMDctNS4xMDRjMC0yLjgyLDIuMjg3LTUuMTA3LDUuMTA3LTUuMTA3YzIuODIyLDAsNS4xMDcsMi4yODcsNS4xMDcsNS4xMDcgICAgQzE2OS45ODksMTM3LjcwNSwxNjcuNzA0LDEzOS45OSwxNjQuODgyLDEzOS45OXoiIGZpbGw9IiM1RTg4OUUiLz48L2c+PGc+PHBhdGggZD0iTTc5LjA3LDE5MC4yMzJjMCwxLjUzNi0xLjI1NiwyLjc5Mi0yLjc5MiwyLjc5MmgtNDAuOTNjLTEuNTM0LDAtMi43OTEtMS4yNTYtMi43OTEtMi43OTJ2LTMuNzIxICAgIGMwLTEuNTMyLDEuMjU2LTIuNzksMi43OTEtMi43OWg0MC45M2MxLjUzNiwwLDIuNzkyLDEuMjU4LDIuNzkyLDIuNzlWMTkwLjIzMnoiIGZpbGw9IiM1RTg4OUUiLz48cGF0aCBkPSJNMTY3LjQ0MiwxOTAuMjMyYzAsMS41MzYtMS4yNTgsMi43OTItMi43OTIsMi43OTJoLTQwLjkzYy0xLjUzNiwwLTIuNzktMS4yNTYtMi43OS0yLjc5MnYtMy43MjEgICAgYzAtMS41MzIsMS4yNTQtMi43OSwyLjc5LTIuNzloNDAuOTNjMS41MzQsMCwyLjc5MiwxLjI1OCwyLjc5MiwyLjc5VjE5MC4yMzJ6IiBmaWxsPSIjNUU4ODlFIi8+PHBhdGggZD0iTTExMC44MTIsMTc3LjU2M2MtMC41NDgtMC41NDgtMS4yNjItMC44MTktMS45NzctMC44MTloLTQuMTg2di02LjUxMmMwLTAuNzE0LTAuMjctMS40MjUtMC44MTUtMS45NzMgICAgYy0wLjU0My0wLjU0NS0xLjI2LTAuODE3LTEuOTczLTAuODE3aC0zLjcyMWMtMC43MTUsMC0xLjQyOSwwLjI3Mi0xLjk3NSwwLjgxN2MtMC41NDUsMC41NDgtMC44MTksMS4yNTktMC44MTksMS45NzN2Ni41MTIgICAgaC00LjE4NWMtMC43MSwwLTEuNDI1LDAuMjcxLTEuOTczLDAuODE5Yy0wLjU0MywwLjU0My0wLjgxOSwxLjI1OC0wLjgxOSwxLjk3M3YxNy42NzRjMCwwLjcxNSwwLjI3NiwxLjQyOSwwLjgxOSwxLjk3NSAgICBjMC41NDgsMC41NDQsMS4yNjMsMC44MTUsMS45NzMsMC44MTVoMTcuNjc0YzAuNzE1LDAsMS40MjktMC4yNzEsMS45NzctMC44MTVjMC41NDQtMC41NDYsMC44MTctMS4yNiwwLjgxNy0xLjk3NXYtMTcuNjc0ICAgIEMxMTEuNjI5LDE3OC44MjEsMTExLjM1NSwxNzguMTA2LDExMC44MTIsMTc3LjU2M3oiIGZpbGw9IiM1RTg4OUUiLz48L2c+PC9nPjwvc3ZnPg==",
                        }],
                        "valueAxes": [{
                            "maximum": that.loadInfos.count*1.5,
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
                                "text": that.strings.countFilesTitle
                            }
                        ],
                        "export": {
                            "enabled": false
                        }
                    });
            },
        }
    }
</script>
