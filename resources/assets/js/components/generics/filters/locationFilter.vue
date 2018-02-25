<template>
    <div :id="_uid" class="ui fluid search filter">
        <div :class="!wantSearch ? 'ui fluid action left icon input' : 'ui fluid left icon input'">
            <i class="map marker alternate icon"></i>
            <input id="filter_location" :class="wantSearch==true ? 'prompt' : 'prompt disabled'" type="text" :placeholder="strings.placeHolder" v-on:autocompletechange="filterChange" :style="withNullBorderRadiusBottom ?  'border-bottom-left-radius: 0; border-bottom-right-radius: 0;':''">
            <button class="ui red icon button" v-if="!wantSearch" v-on:click="resetSearch(true)">
                <i class="remove icon"></i>
            </button>
        </div>
    </div>
</template>

<script>
  /**
   * Props
   *  - accurateList: Array of the possible accurate geolocation. ["locality", "postal_code", "administrative_area_level_2", ...]
   *  - withNullBorderRadiusBottom: Boolean. To disable the border bottom radius
   *
   * Events:
   *  @locationUpdate: emit the object of location on the input {locality: 'joué-les-tours', ..., locationFor: 'Joué-Lès-Tous'}
   *  @clearLocationResults: emit when the input is cleared
   */
  import { DestockTools } from '../../../destockTools'
  export default {
    props: {
      accurateList: Array,
      withNullBorderRadiusBottom: {
        type: Boolean,
        default: false,
        required: false
      }
    },
    computed: {
      strings () {
        return this.$store.state.strings['location-filter']
      }
    },
    data () {
      return {
        wantSearch: true
      }
    },
    mounted () {
      this.updateSearch()
    },
    methods: {
      filterChange () {
        let that = this
        let addressComponents = JSON.parse(sessionStorage.getItem('autoCompleteResult'))
        let parsed = this.parseAddressComponent(addressComponents)
        let event = {}
        this.accurateList.forEach(function (elem, index) {
          if (elem in parsed) {
            event[elem] = parsed[elem]
            that.wantSearch = false
          }
          else {
            event[elem] = null
          }
        })
        event['forLocation'] = $('#filter_location').val()
        if (!this.wantSearch) {
          this.$emit('locationUpdate', event)
        }
      },
      parseAddressComponent (addressComponents) {
        let parsed = {}
        for (let index in addressComponents) {
          parsed[(addressComponents[index]).types[0]] = (addressComponents[index]).short_name
        }
        return parsed
      },
      resetSearch (withEmit) {
        $('#filter_location').val('')
        this.wantSearch = true
        if (withEmit) { this.$emit('clearLocationResults') }
      },
      updateSearch () {
        let previousVal = DestockTools.findInUrl('forLocation')
        if (previousVal && previousVal.length > 0) {
          $('#filter_location').val(previousVal)
          this.wantSearch = false
        }
        else {
          this.resetSearch(false)
        }
      }
    }
  }
</script>
