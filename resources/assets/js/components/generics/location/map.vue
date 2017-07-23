<template>
    <div>
        <div id="geoloc" class="ui icon info message" :data-lng="dataLng" :data-lat="dataLat" :data-geoloc="dataGeoloc" v-on:geochange="latLngChange">
            <i class="marker icon"></i>
            <div class="content">
                <div class="header"></div>
                <p>{{ strings.geolocHelpMsg }}</p>
            </div>
        </div>
        <div class="field">
            <input id="mapInput" class="controls" type="text" :placeholder="strings.geolocHelpMsgTwo">
        </div>
        <div id="map" style="height: 50vh;width: 100%;max-width: 600px;"></div>
    </div>
</template>

<script>
    export default {
        props: {
            lng: String,
            lat: String,
            geoloc: String,
            square: {
                type: Boolean,
                required: false,
                default: false
            },
            resize: {
                type: Boolean,
                required: false,
            }
        },
        data: () => {
            return {
                strings: {},
                dataLng: '',
                dataLat: '',
                dataGeoloc: '',
            }
        },
        mounted () {
            this.strings = this.$store.state.strings['googleMap'];
            this.dataLng = this.lng;
            this.dataLat = this.lat;
            this.dataGeoloc = this.geoloc;
            if(this.square){
                //this.setSizeMap();
            }
            this.$watch('resize', function () {
                this.setSizeMap();
            })
        },
        methods: {
            latLngChange (event) {
                this.$parent.$emit('locationChange', {'lat' : event.target.dataset.lat, 'lng':event.target.dataset.lng, 'geoloc': event.target.dataset.geoloc})
            },
            setSizeMap () {
                let map = $('#map');
                map.css({height: map.width()});
                window.destockMap.resize();
            }
        }
    }
</script>
