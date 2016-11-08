<template>
    <div>
        <div id="geoloc" class="ui icon info message" :data-lng="dataLng" :data-lat="dataLat" :data-geoloc="dataGeoloc" v-on:geochange="latLngChange">
            <i class="marker icon"></i>
            <div class="content">
                <div class="header"></div>
                <p>{{ geolocHelpMsg }}</p>
            </div>
        </div>
        <div class="field">
            <input id="mapInput" class="controls" type="text" :placeholder="geolocHelpMsgTwo">
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
            geolocHelpMsg: String,
            geolocHelpMsgTwo: String
        },
        data: () => {
            return {
                dataLng: '',
                dataLat: '',
                dataGeoloc: '',
            }
        },
        mounted () {
            this.dataLng = this.lng;
            this.dataLat = this.lat;
            this.dataGeoloc = this.geoloc;
        },
        methods: {
            latLngChange (event) {
                this.$parent.$emit('locationChange', {'lat' : event.target.dataset.lat, 'lng':event.target.dataset.lng, 'geoloc': event.target.dataset.geoloc})
            }
        }
    }
</script>
