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
            resize: {
                type: Boolean,
                required: false,
            }
        },
        computed: {
            strings () {
                return this.$store.state.strings['googleMap']
            }
        },
        watch: {
            resize () {
                this.setSizeMap()
            }
        },
        data () {
            return {
                dataLng: this.lng,
                dataLat: this.lat,
                dataGeoloc: this.geoloc
            }
        },
        methods: {
            latLngChange (event) {
                this.$emit('locationChange', {'lat' : event.target.dataset.lat, 'lng':event.target.dataset.lng, 'geoloc': event.target.dataset.geoloc})
            },
            setSizeMap () {
                let map = $('#map');
                map.css({height: map.width()});
                window.destockMap.resize();
            }
        }
    }
</script>
