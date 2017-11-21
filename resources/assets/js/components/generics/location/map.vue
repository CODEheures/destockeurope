<template>
    <div>
        <div id="geoloc" class="ui icon info message" data-lng="" data-lat="" data-geoloc="" v-on:geochange="latLngChange">
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
  /**
   * Props
   *  - resize: Boolean. When resize is necessary
   *
   * Events:
   *  @locationChange: emit on a pin map change: {lat: 12.111xx, lng: 12.123xxx, geoloc: 'my place name'}
   */
  export default {
    props: {
      resize: {
        type: Boolean,
        required: false
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
    methods: {
      latLngChange (event) {
        this.$emit('locationChange', {'lat': event.target.dataset.lat, 'lng': event.target.dataset.lng, 'geoloc': event.target.dataset.geoloc})
      },
      setSizeMap () {
        let map = $('#map')
        map.css({height: map.width()})
        window.destockMap.resize()
      }
    }
  }
</script>
